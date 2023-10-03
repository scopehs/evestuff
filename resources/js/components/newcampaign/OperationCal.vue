<template>
  <div>
    <q-btn
      color="accent"
      flat
      padding="none"
      size="md"
      icon="fa-solid fa-calculator"
      @click="confirm = !confirm"
      round
    />

    <q-dialog v-model="confirm" persistent>
      <q-card class="myRoundTop" style="width: 1200px; max-width: 80vw">
        <q-card-section class="bg-primary text-center">
          <h5 class="no-margin">Simple ADM Calculator</h5>
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col-12">
              <q-slider
                v-model="adm"
                :min="1"
                :max="6"
                :step="0.1"
                :color="color"
                label-always
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="row" no-gutters>
                <div class="col-8">Time To Reinforce</div>
                <div class="col-4">{{ ttr }} - Minutes</div>
              </div>
              <div class="row" no-gutters>
                <div class="col-8">Time Per Command Node (without Spool)</div>
                <div class="col-4">{{ tpcn }} - Minutes</div>
              </div>
              <div class="row" no-gutters>
                <div class="col-8">Best Case Contest (Inclide T1 Spool)</div>
                <div class="col-4">{{ bcc }} - Minutes</div>
              </div>
              <div class="row" no-gutters>
                <div class="col-8">Contested (Best Guess)</div>
                <div class="col-4">{{ c }} - Minutes</div>
              </div>
            </div>
            <div class="col-6">
              <q-markup-table dense>
                <thead>
                  <tr>
                    <th class="text-left">Total Activiy Multiplier</th>
                    <th class="text-right">Time to Reinforce a Structure</th>
                    <th class="text-right">Time to Capture a Command Node</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center text-webway">1x</td>
                    <td class="text-center text-webway">10</td>
                    <td class="text-center text-webway">4</td>
                  </tr>
                  <tr>
                    <td class="text-center text-webway">2x</td>
                    <td class="text-center text-webway">20</td>
                    <td class="text-center text-webway">8</td>
                  </tr>
                  <tr>
                    <td class="text-center text-webway">3.5x</td>
                    <td class="text-center text-webway">35</td>
                    <td class="text-center text-webway">14</td>
                  </tr>
                  <tr>
                    <td class="text-center text-webway">4.5x</td>
                    <td class="text-center text-webway">45</td>
                    <td class="text-center text-webway">18</td>
                  </tr>
                  <tr>
                    <td class="text-center text-webway">4.9x</td>
                    <td class="text-center text-webway">49</td>
                    <td class="text-center text-webway">19.6</td>
                  </tr>
                  <tr>
                    <td class="text-center text-webway">5.4x</td>
                    <td class="text-center text-webway">54</td>
                    <td class="text-center text-webway">21.6</td>
                  </tr>
                  <tr>
                    <td class="text-center text-webway">6x</td>
                    <td class="text-center text-webway">60</td>
                    <td class="text-center text-webway">24</td>
                  </tr>
                </tbody>
              </q-markup-table>
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
let confirm = $ref(false);
let adm = $ref(1);

let color = $computed(() => {
  if (adm > 0 && adm < 2) {
    return "slider1";
  }

  if (adm > 1.9 && adm < 3) {
    return "slider2";
  }

  if (adm > 2.9 && adm < 4) {
    return "slider3";
  }

  if (adm > 3.9 && adm < 5) {
    return "slider4";
  }

  if (adm > 4.9 && adm < 7) {
    return "slider5";
  }

  return "slider6";
});

let ttr = $computed(() => {
  var num = 10 * adm;
  return num.toFixed(2);
});

let tpcn = $computed(() => {
  var num = 4 * adm;
  return num.toFixed(2);
});

let bcc = $computed(() => {
  var num = (adm + 8) * 2.2;
  return num.toFixed(2);
});

let c = $computed(() => {
  var num = (adm + 8) * 3.4;
  return num.toFixed(2);
});
</script>

<style lang="sass"></style>
