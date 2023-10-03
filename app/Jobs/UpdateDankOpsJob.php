<?php

namespace App\Jobs;

use App\Models\DankOperation;
use App\Models\OperationInfoDoctrine;
use App\Models\OperationInfoFleet;
use App\Models\OperationInfoUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdateDankOpsJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $dankOpID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dankOpID)
    {
        $this->dankOpID = $dankOpID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // $activeOps = DankOperation::whereNull('closed_at')->get();
        $active = DankOperation::where('id', $this->dankOpID)->first();


        $fleetURL = "https://fleets.apps.gnf.lt/api/v1/coordination/operation/" . $active->dank_id . "/fleets";
        $opURL = "https://fleets.apps.gnf.lt/api/v1/coordination/operation/" . $active->dank_id;

        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $token = env('DANK_TOKEN', ($variables && array_key_exists('DANK_TOKEN', $variables)) ? $variables['DANK_TOKEN'] : 'null');
        $response = Http::withHeaders([
            'X-ApiKey' => $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get($fleetURL);

        $fleets =  $response->json();

        $response = Http::withHeaders([
            'X-ApiKey' => $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get($opURL);

        $operation =  $response->json();
        $active->closed_at = $operation['closedAt'] ?? null;
        $active->name = $operation['name'];
        $active->save();
        if ($active->wasChanged([
            'closed_at',
            'name'
        ])) {

            operationInfoSoloPageAddDankOp($active->operation_info_id, 20);
        }

        foreach ($fleets as $fleet) {

            $bossCheck = OperationInfoUser::where('id', $fleet['fleetBossId'])->first();
            if (!$bossCheck) {
                $url = "https://images.evetech.net/characters/" . $fleet['fleetBossId'] . "/portrait?tenant=tranquility&size=64";
                $new = new OperationInfoUser();
                $new->id = $fleet['fleetBossId'];
                $new->name = $fleet['fleetBoss'];
                $new->url = $url;
                $new->corporation_id = $fleet['fleetBossCorporationId'];
                $new->alliance_id = $fleet['fleetBossAllianceId'];
                $new->save();
            }

            $fcCheck = OperationInfoUser::where('id', $fleet['fleetCommanderId'])->first();
            if (!$fcCheck) {
                $url = "https://images.evetech.net/characters/" . $fleet['fleetCommanderId'] . "/portrait?tenant=tranquility&size=64";
                $new = new OperationInfoUser();
                $new->id = $fleet['fleetCommanderId'];
                $new->name = $fleet['fleetCommander'];
                $new->url = $url;
                $new->corporation_id = $fleet['fleetCommanderCorporationId'];
                $new->alliance_id = $fleet['fleetCommanderAllianceId'];
                $new->save();
            }

            $old = OperationInfoFleet::where('uid', $fleet['id'])->first();
            $docID = OperationInfoDoctrine::where('name', $fleet['setupName'])->first();
            if ($old) {

                $old->uid = $fleet['id'];
                $old->name = $fleet['name'];
                $old->started = $fleet['startedAt'];
                $old->closed = $fleet['closedAt'];
                $old->fc_id = $fleet['fleetCommanderId'];
                $old->boss_id = $fleet['fleetBossId'];
                $old->dank_operation_id = $active->id;
                $old->doctrine_id = $docID->id ?? null;
                $old->save();

                if ($old->wasChanged(['name', 'fc_id', 'boss_id', 'doctrine_id'])) {
                    operationInfoSoloPageFleetBroadcast($old->id, $old->operation_info_id, 2);
                }
            } else {

                $checkCustomFleet = OperationInfoFleet::where('uid', '!=', $fleet['id'])
                    ->where('fc_id', $fleet['fleetCommanderId'])
                    ->first();

                if ($checkCustomFleet) {
                    $checkCustomFleet->uid = $fleet['id'];
                    $checkCustomFleet->name = $fleet['name'];
                    $checkCustomFleet->started = $fleet['startedAt'];
                    $checkCustomFleet->closed = $fleet['closedAt'];
                    $checkCustomFleet->fc_id = $fleet['fleetCommanderId'];
                    $checkCustomFleet->boss_id = $fleet['fleetBossId'];
                    $checkCustomFleet->dank_operation_id = $operation['id'];
                    $checkCustomFleet->doctrine_id = $docID->id ?? null;
                    $checkCustomFleet->save();
                    operationInfoSoloPageFleetBroadcast($checkCustomFleet->id, $checkCustomFleet->operation_info_id, 2);
                } else {
                    $new = new OperationInfoFleet();
                    $new->uid = $fleet['id'];
                    $new->name = $fleet['name'];
                    $new->started = $fleet['startedAt'];
                    $new->closed = $fleet['closedAt'];
                    $new->fc_id = $fleet['fleetCommanderId'];
                    $new->boss_id = $fleet['fleetBossId'];
                    $new->dank_operation_id = $operation['id'];
                    $new->doctrine_id = $docID->id ?? null;
                    $new->operation_info_id = $active->operation_info_id;
                    $new->save();
                    operationInfoSoloPageFleetBroadcast($new->id, $new->operation_info_id, 2);
                }
            }
        }
    }
}
