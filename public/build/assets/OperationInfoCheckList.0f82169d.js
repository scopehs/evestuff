import{d as h,_ as T,u as Q,r as A,c as l,h as p,e as B,f as e,w as r,z as E,A as u,p as V,q as L,aD as j,B as q,j as d,l as t,n as f,m as _,aE as m,C as g,aX as x}from"./app.10579c01.js";import{Q as z}from"./QCircularProgress.68264667.js";import{Q as c}from"./QSlideTransition.6d4d03a3.js";import{Q as G,a as v}from"./QTabPanels.1a4b62ce.js";const M={class:"no-margin"},U={class:"column q-gutter-none text-webway"},$={class:"column q-gutter-none text-webway"},H={class:"column q-gutter-none text-webway"},Z={__name:"OperationInfoCheckList",setup(X){const n=h(()=>T(()=>import("./OperationInfoCheckListTickBox.d1790cc4.js"),["assets/OperationInfoCheckListTickBox.d1790cc4.js","assets/QCheckbox.bf3f01de.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/use-checkbox.45f82c4c.js"])),a=Q();A("postop");let P=l(()=>a.operationInfoPage.planing_op_posted+a.operationInfoPage.planing_op_pre_ping+a.operationInfoPage.planing_op_recon_alerted+a.operationInfoPage.planing_op_capital_fc_found+a.operationInfoPage.planing_op_fc_found+a.operationInfoPage.planing_op_doctromes_decoded+a.operationInfoPage.planing_op_allies_infored),N=l(()=>a.operationInfoPage.form_op_fc_ready+a.operationInfoPage.form_op_capital_fc_ready+a.operationInfoPage.form_op_recon_ready+a.operationInfoPage.form_op_scouts_ready+a.operationInfoPage.form_op_gsol_ready+a.operationInfoPage.form_op_blackhand_ready+a.operationInfoPage.form_op_gsfoe_ready+a.operationInfoPage.form_op_allies_ready),C=l(()=>a.operationInfoPage.post_op_defrief_done+a.operationInfoPage.post_op_scouts_done+a.operationInfoPage.post_op_recon_done+a.operationInfoPage.post_op_fc_done+a.operationInfoPage.post_op_coord_done),o=l(()=>a.operationInfoPage.status_id),y=l(()=>{if(o.value==1)return"planning";if(o.value==2)return"formup";if(o.value==3)return"postop";if(o.value==4)return""}),k=l(()=>{if(o.value==1)return"Planning";if(o.value==2)return"Pre-OP Form Up";if(o.value==3)return"Post-OP Cool Down";if(o.value==4)return""}),w=l(()=>o.value==1?"Planning":o.value==2?"Form Up":o.value==3?"Post Op":o.value==4?"No Operation":""),D=l(()=>{if(o.value==1)return"positive";if(o.value==2)return"warning";if(o.value==3)return"negative";if(o.value==4)return"grey"}),R=l(()=>o.value==1?"bg-positive":o.value==2?"bg-warning":o.value==3?"bg-negative":o.value==4?"bg-grey-10":""),I=l(()=>{if(o.value==1)return 7;if(o.value==2)return 8;if(o.value==3)return 5;if(o.value==4)return 0}),b=l(()=>{if(o.value==1)return P.value;if(o.value==2)return N.value;if(o.value==3)return C.value;if(o.value==4)return 0}),O=F=>{a.operationInfoPage.status_id=F;var i=a.operationInfoPage;x({method:"put",url:"/api/operationinfopage/"+a.operationInfoPage.id,withCredentials:!0,data:i,headers:{Accept:"application/json","Content-Type":"application/json"}})},S=()=>{x({method:"DELETE",url:"/api/operationinfosheet/"+a.operationInfoPage.link,withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}})};return(F,i)=>(p(),B("div",null,[e(_,{color:D.value,label:w.value,rounded:""},{default:r(()=>[e(z,{"show-value":"",class:"q-ml-xs",value:b.value,min:0,max:I.value,rounded:"","font-size":"0.5rem",size:"sm",color:"light-blue"},{default:r(()=>[E(u(b.value)+"/"+u(I.value),1)]),_:1},8,["value","max"]),e(V,{class:"myRoundTop"},{default:r(()=>[e(L,{class:"myRoundTop"},{default:r(()=>[e(j,{class:q([R.value,"text-h5 text-center"])},{default:r(()=>[d("h5",M,u(k.value),1)]),_:1},8,["class"]),e(G,{modelValue:y.value,"onUpdate:modelValue":i[3]||(i[3]=s=>y.value=s),animated:""},{default:r(()=>[e(v,{name:"planning"},{default:r(()=>[d("div",U,[e(t(n),{title:"Fourm OP Posted",storeName:"planing_op_posted",tagName:"PlanningPosted"}),e(t(n),{title:"OP Pre-Pinged",storeName:"planing_op_pre_ping",tagName:"PrePing"}),e(t(n),{title:"Recon Informed",storeName:"planing_op_recon_alerted",tagName:"ReconAlerted"}),e(t(n),{title:"Capaital FC's Found",storeName:"planing_op_capital_fc_found",tagName:"CapitalFCFound"}),e(t(n),{title:"FC's Found",storeName:"planing_op_fc_found",tagName:"FCFound"}),e(t(n),{title:"Doctrines decided",storeName:"planing_op_doctromes_decoded",tagName:"DoctrinesDecided"}),e(t(n),{title:"Allies Informed",storeName:"planing_op_allies_infored",tagName:"AlliesInformed"})]),e(c,null,{default:r(()=>[P.value==7?(p(),f(m,{key:0,align:"center"},{default:r(()=>[e(_,{color:"positive",rounded:"",label:"Next",onClick:i[0]||(i[0]=s=>t(O)(2))})]),_:1})):g("",!0)]),_:1})]),_:1}),e(v,{name:"formup"},{default:r(()=>[d("div",$,[e(t(n),{title:"FC's ready and informed",storeName:"form_op_fc_ready",tagName:"FCReady"}),e(t(n),{title:"Capital FC's ready and informed",storeName:"form_op_capital_fc_ready",tagName:"CapitalFCReady"}),e(t(n),{title:"Recon Online",storeName:"form_op_recon_ready",tagName:"ReconReady"}),e(t(n),{title:"Scouts Onlined",storeName:"form_op_scouts_ready",tagName:"ScoutsReady"}),e(t(n),{title:"GSOL Online",storeName:"form_op_gsol_ready",tagName:"GSOLReady"}),e(t(n),{title:"Black Hand Online",storeName:"form_op_blackhand_ready",tagName:"BlackHandReady"}),e(t(n),{title:"GSFOE Online",storeName:"form_op_gsfoe_ready",tagName:"GSFOEReady"}),e(t(n),{title:"Allies Confirmed",storeName:"form_op_allies_ready",tagName:"AlliesReady"})]),e(c,null,{default:r(()=>[N.value==8?(p(),f(m,{key:0,align:"center"},{default:r(()=>[e(_,{color:"positive",rounded:"",label:"Next",onClick:i[1]||(i[1]=s=>t(O)(3))})]),_:1})):g("",!0)]),_:1})]),_:1}),e(v,{name:"postop"},{default:r(()=>[d("div",H,[e(t(n),{title:"Post-Op Debrief done",storeName:"post_op_defrief_done",tagName:"PostOpDebriefDone"}),e(t(n),{title:"Scouts Stood Down",storeName:"post_op_scouts_done",tagName:"ScoutsDone"}),e(t(n),{title:"Recon Stood Down",storeName:"post_op_recon_done",tagName:"ReconDone"}),e(t(n),{title:"FC's debriefed and stood down",storeName:"post_op_fc_done",tagName:"FCDone"}),e(t(n),{title:"Coord debriefed",storeName:"post_op_coord_done",tagName:"CoordDone"})]),e(c,null,{default:r(()=>[C.value==5?(p(),f(m,{key:0,align:"center"},{default:r(()=>[e(_,{color:"negative",rounded:"",label:"Close Op",onClick:i[2]||(i[2]=s=>t(S)())})]),_:1})):g("",!0)]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})]),_:1})]),_:1},8,["color","label"])]))}};export{Z as default};
