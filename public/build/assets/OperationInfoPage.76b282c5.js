import{a as F,az as H,u as j,i as W,d as _,o as G,b as J,c as t,e as S,f as o,w as n,q as x,g as Y,_ as g,h as f,aD as w,j as s,m as $,p as K,z as X,l as r,aP as c,A as b,n as d,C as u}from"./app.10579c01.js";import{Q as Z}from"./QOptionGroup.2acbdfed.js";import"./QRadio.4f3e8d58.js";import"./use-checkbox.45f82c4c.js";import"./QCheckbox.bf3f01de.js";import"./QToggle.a02dc2d0.js";const ee={class:"q-ma-md"},ae={class:"row full-width justify-between"},te={class:"col-auto flex align-baseline"},oe=s("h5",{class:"no-margin"},"Page Setting",-1),ne={class:"row items-center"},ie={class:"col flex flex-center q-pl-lg"},se={class:"col-auto"},le={class:"no-margin"},re={class:""},fe={class:"row justify-between"},_e={key:0,class:"col-3 q-pr-lg"},ge={class:"column q-gutter-lg"},ue={class:"col-9 col-grow"},de={class:"column q-gutter-lg"},Oe={__name:"OperationInfoPage",setup(pe){Y(e=>({"37ff5e13":A.value,"5a681461":U.value,"6cb6f9cc":M.value,da816caa:Q.value,"64d44c00":N.value,"37d111a0":B.value}));let k=F(),R=H(),a=j();W("can");const E=_(()=>g(()=>import("./OperationInfoReconCard.6f9bd87d.js"),["assets/OperationInfoReconCard.6f9bd87d.js","assets/app.10579c01.js","assets/app.4fce15f2.css"])),T=_(()=>g(()=>import("./OperationInfoSettingPannel.f2d98bba.js"),["assets/OperationInfoSettingPannel.f2d98bba.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QSelect.32be28f9.js","assets/QItemLabel.1f7b55df.js","assets/QToggle.a02dc2d0.js","assets/use-checkbox.45f82c4c.js","assets/QOptionGroup.2acbdfed.js","assets/QRadio.4f3e8d58.js","assets/QCheckbox.bf3f01de.js"])),L=_(()=>g(()=>import("./OpertationInfoSystemTable.cb7e808c.js"),["assets/OpertationInfoSystemTable.cb7e808c.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QTd.f19052d1.js","assets/QTr.00779787.js","assets/QTable.4d294d9e.js","assets/QSeparator.0566634d.js","assets/QMarkupTable.d2f16f87.js","assets/QSelect.32be28f9.js","assets/QItemLabel.1f7b55df.js","assets/QLinearProgress.84b4c331.js","assets/QCheckbox.bf3f01de.js","assets/use-checkbox.45f82c4c.js"])),C=_(()=>g(()=>import("./OperationInfoFleetCard.06ea2f28.js"),["assets/OperationInfoFleetCard.06ea2f28.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QTable.4d294d9e.js","assets/QSeparator.0566634d.js","assets/QMarkupTable.d2f16f87.js","assets/QSelect.32be28f9.js","assets/QItemLabel.1f7b55df.js","assets/QLinearProgress.84b4c331.js","assets/QCheckbox.bf3f01de.js","assets/use-checkbox.45f82c4c.js"])),V=_(()=>g(()=>import("./OperationInfoCheckList.0f82169d.js"),["assets/OperationInfoCheckList.0f82169d.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QCircularProgress.68264667.js","assets/QSlideTransition.6d4d03a3.js","assets/QTabPanels.1a4b62ce.js"])),z=_(()=>g(()=>import("./OperationInfoMessageCard.009ea11a.js"),["assets/OperationInfoMessageCard.009ea11a.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QBadge.6984755e.js","assets/QChatMessage.057585fe.js","assets/OperationInfoMessageCard.f247a9ce.css"])),D=_(()=>g(()=>import("./OperationInfoSystemWatchTable.ff3bda6e.js"),["assets/OperationInfoSystemWatchTable.ff3bda6e.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QTd.f19052d1.js","assets/QTable.4d294d9e.js","assets/QSeparator.0566634d.js","assets/QMarkupTable.d2f16f87.js","assets/QSelect.32be28f9.js","assets/QItemLabel.1f7b55df.js","assets/QLinearProgress.84b4c331.js","assets/QCheckbox.bf3f01de.js","assets/use-checkbox.45f82c4c.js"]));G(async()=>{await a.getOperationSheetInfoPage(R.params.link),await a.getOperationUsers(),await a.getOperationInfoMumble(),await a.getOperationInfoDoctrines(),await a.getOperationRecon(),await a.getAllianceTickList(),await a.getSystemList(),await a.getOperationInfoReconRoles(),await a.getOperationSheetInfoOperationList(),await a.getOperationInfoJamList(),await a.getUserList(),Echo.private("operationinfooppageown."+a.user_id+"-"+l.value.id),Echo.private("operationinfooppage."+l.value.id).listen("OperationInfoPageSoloUpdate",e=>{e.flag.flag==1&&a.updateOperationSheetInfoPage(e.flag.message),e.flag.flag==2&&a.updateOperationSheetInfoPageFleet(e.flag.message),e.flag.flag==3&&(a.operationInfoUsers=e.flag.message),e.flag.flag==4&&(a.operationInfoRecon=e.flag.message),e.flag.flag==5&&a.updateOperationReconSolo(e.flag.message),e.flag.flag==6&&a.deleteOperationSheetInfoPageFleet(e.flag.message),e.flag.flag==7&&a.updateOperationMessage(e.flag.message),e.flag.flag==8&&(a.operationInfoPage.status=e.flag.message),e.flag.flag==9&&(a.operationInfoPage.operation=e.flag.message),e.flag.flag==10&&(a.operationInfoPage.operation=e.flag.message),e.flag.flag==11&&(a.operationInfoPage.systems=e.flag.message),e.flag.flag==12&&a.getOperationInfoDoctrines(),e.flag.flag==13&&a.removeOperationReconSolo(e.flag.message),e.flag.flag==14&&a.updateOperationSoloSystems(e.flag.message),e.flag.flag==15&&(a.clearOperationInfoSolo(),k.push({path:"/operationinfoover"})),e.flag.flag==16&&a.updateNewCampaignSystemInfo(e.flag.message),e.flag.flag==17&&a.updateOperationCampaignsSolo(e.flag.message),e.flag.flag==18&&(a.operationInfoUserList=e.flag.message),e.flag.flag==19&&(a.operationInfoPage.fleets=e.flag.message),e.flag.flag==20&&(a.operationInfoPage.dankop=e.flag.message),e.flag.flag==21&&(a.operationInfoPage.watch_systems=e.flag.message),e.flag.flag==22&&(a.operationInfoPage.dankop=null)}),document.title="Evestuff - Operation Page - "+l.value.name}),J(async()=>{Echo.leave("operationinfooppage."+l.value.id),Echo.leave("operationinfooppageown."+a.user_id+"-"+l.value.id)});let l=t(()=>a.operationInfoPage),I=t(()=>!!(p.value.showFleets&&l.value.fleet_table)),v=t(()=>!!(p.value.showReconTable&&l.value.recon_table)),O=t(()=>!!(p.value.showSystemTable&&l.value.system_table)),y=t(()=>!!(p.value.showCheckList&&l.value.check_list)),m=t(()=>!!(p.value.showWatchedSystems&&l.value.watched_system_table)),p=t(()=>a.operationInfoSetting),A=t(()=>{let e=30;return a.size.height-e+"px"}),P=t({get:()=>a.getOperationInfoTableSettings,set:e=>{a.setOwnOptions(e)}}),q=t(()=>!!(v.value||m.value)),U=t(()=>{if(m.value){let e=70;return a.size.height/2-e+"px"}else{let e=100;return a.size.height-e+"px"}}),M=t(()=>{if(m.value){let e=150;return a.size.height/2-e+"px"}else{let e=170;return a.size.height-e+"px"}}),B=t(()=>{if(v.value){let e=70;return a.size.height/2-e+"px"}else{let e=100;return a.size.height-e+"px"}}),N=t(()=>{if(O.value){let e=60;return a.size.height/2-e+"px"}else{let e=120;return a.size.height-e+"px"}}),Q=t(()=>{if(I.value){let e=80;return a.size.height/2-e+"px"}else{let e=120;return a.size.height-e+"px"}});return(e,i)=>(f(),S("div",ee,[o(x,{class:"myRoundTop myOperationInfoMainCard"},{default:n(()=>[o(w,{class:"bg-primary text-center q-py-xs"},{default:n(()=>[s("div",ae,[s("div",te,[o($,{"text-color":"warning",flat:"",icon:"fa-solid fa-eye",rounded:"",padding:"none"},{default:n(()=>[o(K,{class:"myRoundTop"},{default:n(()=>[o(x,{class:"myRoundTop text-webway"},{default:n(()=>[o(w,{class:"bg-primary text-h5 text-center"},{default:n(()=>[oe]),_:1}),o(w,null,{default:n(()=>[X(" Your Own Setting for this page "),o(Z,{class:"q-pt-md",modelValue:P.value,"onUpdate:modelValue":i[0]||(i[0]=h=>P.value=h),options:r(a).operationInfoSettingOpetions,color:"yellow",dense:"",type:"toggle"},{label:n(h=>[s("div",ne,[o(c,{mode:"out-in","enter-active-class":"animate__animated animate__flash","leave-active-class":"animate__animated animate__flash"},{default:n(()=>[s("span",null,b(h.label),1)]),_:2},1024)])]),_:1},8,["modelValue","options"])]),_:1})]),_:1})]),_:1})]),_:1}),o(c,{mode:"out-in","enter-active-class":"animate__animated animate__fadeIn animate__slower","leave-active-class":"animate__animated animate__fadeOut animate__slower"},{default:n(()=>[y.value?(f(),d(r(V),{key:`${y.value}-ownSetting`,class:"q-pl-sm"})):u("",!0)]),_:1}),s("div",ie,[r(a).operationInfoPage.messages?(f(),d(r(z),{key:0})):u("",!0)])]),s("div",se,[s("h5",le,"Operation - "+b(l.value.name),1)]),s("div",re,[o(r(T))])])]),_:1}),o(w,null,{default:n(()=>[s("div",fe,[o(c,{mode:"out-in","enter-active-class":"animate__animated animate__backInLeft animate__slower","leave-active-class":"animate__animated animate__backOutLeft animate__slower"},{default:n(()=>[q.value?(f(),S("div",_e,[s("div",ge,[v.value?(f(),d(r(E),{key:0})):u("",!0),m.value?(f(),d(r(D),{key:1})):u("",!0)])])):u("",!0)]),_:1}),s("div",ue,[s("div",de,[o(c,{mode:"out-in","enter-active-class":"animate__animated animate__backInDown animate__slower","leave-active-class":"animate__animated animate__backOutUp animate__slower"},{default:n(()=>[O.value?(f(),d(r(L),{key:0})):u("",!0)]),_:1}),o(c,{mode:"out-in","enter-active-class":"animate__animated animate__backInUp animate__slower","leave-active-class":"animate__animated animate__backOutDown animate__slower"},{default:n(()=>[I.value?(f(),d(r(C),{key:0})):u("",!0)]),_:1})])])])]),_:1})]),_:1})]))}};export{Oe as default};
