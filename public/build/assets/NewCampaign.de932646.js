import{az as M,u as B,i as x,d as _,o as L,b as N,c as r,e as h,j as u,f as l,l as o,F as q,t as H,w as s,ah as I,_ as C,h as c,n as P,aP as Q,q as A,aD as f,z as p,aE as k,m as v}from"./app.10579c01.js";const $={class:"q-ma-md"},F={class:"row"},j={class:"col q-pb-md"},z={class:"row"},K={class:"col"},W={class:"row"},Y=u("h5",{class:"no-margin"},"MAKE SURE TO ADD A CHARACTER",-1),G=u("h5",{class:"no-margin"},"WHAT TO DO",-1),Z={__name:"NewCampaign",setup(J){let D=M(),a=B(),T=x("can");const b=_(()=>C(()=>import("./CampaignTitleBar.aef69086.js"),["assets/CampaignTitleBar.aef69086.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QExpansionItem.02e04bbc.js","assets/QItemLabel.1f7b55df.js","assets/QSlideTransition.6d4d03a3.js","assets/QSeparator.0566634d.js"])),R=_(()=>C(()=>import("./CampaignSystemCard.e90676da.js"),["assets/CampaignSystemCard.e90676da.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QSeparator.0566634d.js","assets/QExpansionItem.02e04bbc.js","assets/QItemLabel.1f7b55df.js","assets/QSlideTransition.6d4d03a3.js"])),V=_(()=>C(()=>import("./CampaignActiveBar.4cfbd2e6.js"),["assets/CampaignActiveBar.4cfbd2e6.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QToggle.a02dc2d0.js","assets/use-checkbox.45f82c4c.js","assets/QTabPanels.1a4b62ce.js","assets/QExpansionItem.02e04bbc.js","assets/QItemLabel.1f7b55df.js","assets/QSlideTransition.6d4d03a3.js","assets/QSeparator.0566634d.js"]));L(async()=>{await a.getOperationInfo(D.params.id),await a.getCampaignsList(i.value),document.title="Evestuff - "+a.newOperationInfo.title,Echo.private("operations."+i.value).listen("OperationUpdate",e=>{e.flag.flag==1,e.flag.flag==2&&(a.newOperationInfo.read_only=e.flag.message),e.flag.flag==3&&(a.newOperationInfo=e.flag.message),e.flag.flag==4&&a.updateNewCampaigns(e.flag.message),e.flag.flag==5&&a.removeCharfromOpList(e.flag.userid),e.flag.flag==6&&a.updateOpChar(e.flag.message),e.flag.flag==7&&a.updateNewCampaignSystem(e.flag.message),e.flag.flag==8&&a.updateNewCampaigns(e.flag.message.campaign[0]),e.flag.flag==9&&(a.state.newCampaignSystems=e.flag.message)}),Echo.private("operationsown."+a.user_id+"-"+i.value).listen("OperationOwnUpdate",e=>{e.flag.flag==1&&(a.newOperationMessageOverlay=parseInt(e.flag.type)),e.flag.flag==2,e.flag.flag==3&&a.updateNewOwnChar(e.flag.message),e.flag.flag==4,e.flag.flag==5&&a.removeCharfromOwnList(e.flag.userid),e.flag.flag==6,e.flag.flag==7,e.flag.flag==8}),T("view_campaign_members")&&Echo.private("operationsadmin."+i.value).listen("OperationAdminUpdate",e=>{e.flag.flag==1&&(a.operationUserList=e.flag.message),e.flag.flag==2,e.flag.flag==3,e.flag.flag==4,e.flag.flag==5,e.flag.flag==6,e.flag.flag==7,e.flag.flag==8})}),N(async()=>{Echo.leave("operations."+i.value),Echo.leave("operationsown."+a.user_id+"-"+i.value),Echo.leave("operationsadmin."+i.value)});let w=()=>{a.newOperationMessageAddChar=!0,a.newOperationMessageOverlay=0},y=r(()=>a.newOperationMessageOverlay==1),O=r(()=>a.newOperationMessageOverlay==2),g=r(()=>{var e=a.newCampaigns.length;if(e>0){var t=a.newCampaigns.filter(n=>n.status_id==2);return t}else return[]}),d=r(()=>{var e=a.newCampaigns.length;if(e>0){var t=a.newCampaigns.filter(n=>n.status_id==5);return t}else return[]}),i=r(()=>a.newOperationInfo.id),E=r(()=>{if(g.value.length>0&&d.value.length>0){let e=g.value.concat(d.value);return e=e.filter((t,n)=>e.indexOf(t)==n),e}else return g.value.length>0?g.value:d.value.length>0?d.value:[]}),S=r(()=>{if(E.value.length>0){var e=E.value.map(t=>t.id);return e}else return[]}),U=r(()=>{var e=a.newCampaignSystems.filter(t=>t.new_campaigns.filter(m=>S.value.includes(m.id)).length>0);return e});return(e,t)=>(c(),h("div",$,[u("div",F,[u("div",j,[l(o(b),{operationID:i.value,title:o(a).newOperationInfo.title,activeCampaigns:g.value,warmUpCampaigns:d.value},null,8,["operationID","title","activeCampaigns","warmUpCampaigns"])])]),u("div",z,[u("div",K,[l(o(V),{operationID:i.value},null,8,["operationID"])])]),u("div",W,[(c(!0),h(q,null,H(U.value,(n,m)=>(c(),h("div",{class:"col-6 q-pa-sm",key:m.id},[l(Q,{name:"custom-classes","enter-active-class":"animate__animated animate__heartBeat animate__repeat-2","leave-active-class":"animate__animated animate__flash",mode:"out-in"},{default:s(()=>[(c(),P(o(R),{key:`${m.id}-card`,item:n,operationID:i.value},null,8,["item","operationID"]))]),_:2},1024)]))),128))]),l(I,{modelValue:y.value,"onUpdate:modelValue":t[2]||(t[2]=n=>y.value=n),persistent:""},{default:s(()=>[l(A,{class:"myRoundTop"},{default:s(()=>[l(f,{class:"bg-primary text-h5 text-center"},{default:s(()=>[Y]),_:1}),l(f,null,{default:s(()=>[p(' Remeber to add any chars you have in the campaign by pressing the green "CHARACTER" button ')]),_:1}),l(k,{align:"right"},{default:s(()=>[l(v,{rounded:"",label:"Close",color:"negative",onClick:t[0]||(t[0]=n=>o(a).newOperationMessageOverlay=0)}),l(v,{rounded:"",label:"Add Character",color:"secondary",onClick:t[1]||(t[1]=n=>o(w)())})]),_:1})]),_:1})]),_:1},8,["modelValue"]),l(I,{modelValue:O.value,"onUpdate:modelValue":t[5]||(t[5]=n=>O.value=n),persistent:""},{default:s(()=>[l(A,{class:"myRoundTop"},{default:s(()=>[l(f,{class:"bg-primary text-h5 text-center"},{default:s(()=>[G]),_:1}),l(f,null,{default:s(()=>[p(" Thanks for participating in this Entosis campaign. In order to participate, we need you to add the characters you are using to actively hack to this tool - don't worry - we don't require an ESI link. ")]),_:1}),l(f,null,{default:s(()=>[p(' To do this, press the "CHARACTERS" button and pick **Hacker** as the role for each alt. ')]),_:1}),l(f,null,{default:s(()=>[p(" Once you have added all of your characters, await instructions from your FC. You will be assigned a specific system, which is done in-game via Squads. ")]),_:1}),l(f,null,{default:s(()=>[p(" Check the name of your squad for the assigned system. Once instructed, move to this system and prepare to hack. If in doubt, ask for help. ")]),_:1}),l(k,{align:"right"},{default:s(()=>[l(v,{rounded:"",label:"Close",color:"negative",onClick:t[3]||(t[3]=n=>o(a).newOperationMessageOverlay=0)}),l(v,{rounded:"",label:"Add Character",color:"secondary",onClick:t[4]||(t[4]=n=>o(w)())})]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{Z as default};