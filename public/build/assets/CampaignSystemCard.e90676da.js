import{u as I,d as n,_ as i,o as C,b as y,r as b,h as g,e as E,f as e,w as l,j as s,A as x,l as r,q as O,aD as P}from"./app.10579c01.js";import{Q as d}from"./QSeparator.0566634d.js";import{Q as S}from"./QExpansionItem.02e04bbc.js";import"./QItemLabel.1f7b55df.js";import"./QSlideTransition.6d4d03a3.js";const V={class:"row q-gutter-none full-width items-center"},A={class:"col-1"},Q={class:"col-3"},T={class:"col-6 flex justify-end full-height align-center"},N={__name:"CampaignSystemCard",props:{item:Object,operationID:Number},setup(t){const o=t;let p=I();const _=n(()=>i(()=>import("./SystemNodeCount.4a1f0145.js"),["assets/SystemNodeCount.4a1f0145.js","assets/QCircularProgress.68264667.js","assets/app.10579c01.js","assets/app.4fce15f2.css"])),v=n(()=>i(()=>import("./OnTheWay.8325d502.js"),["assets/OnTheWay.8325d502.js","assets/app.10579c01.js","assets/app.4fce15f2.css"])),f=n(()=>i(()=>import("./ReadyToGo.c2ef8bc2.js"),["assets/ReadyToGo.c2ef8bc2.js","assets/app.10579c01.js","assets/app.4fce15f2.css"])),h=n(()=>i(()=>import("./CampaignSystemCardContent.6b986bc6.js"),["assets/CampaignSystemCardContent.6b986bc6.js","assets/app.10579c01.js","assets/app.4fce15f2.css"]));C(()=>{Echo.private("operationsown."+p.user_id+"-"+o.operationID).listen("OperationOwnUpdate",c=>{c.flag.flag==8&&(c.flag.type==1?w():D())})}),y(()=>{Echo.leave("operationsown."+p.user_id+"-"+o.operationID)});let a=b(!0),w=()=>{a.value=!0},D=()=>{a.value=!1};return(c,u)=>(g(),E("div",null,[e(S,{class:"shadow-1 overflow-hidden",style:{"border-radius":"30px"},label:"dance","model-value":a.value,"onUpdate:modelValue":u[0]||(u[0]=m=>a.value=m),"expand-icon-toggle":"","header-class":" q-py-none bg-webBack text-webway text-center",onShow:m=>10,onHide:m=>10,dark:""},{header:l(()=>[s("div",V,[s("div",A,x(o.item.system_name),1),e(d,{vertical:"",class:"",color:"webChip"}),s("div",Q,[e(r(_),{item:t.item.new_nodes},null,8,["item"])]),s("div",T,[e(d,{vertical:"",class:"",color:"webChip"}),e(r(v),{class:"q-px-md",operationID:t.operationID,item:t.item},null,8,["operationID","item"]),e(d,{vertical:"",class:"",color:"webChip"}),e(r(f),{class:"q-px-md",operationID:t.operationID,item:t.item},null,8,["operationID","item"]),e(d,{vertical:"",class:"",color:"webChip"})])])]),default:l(()=>[e(O,null,{default:l(()=>[e(P,null,{default:l(()=>[e(r(h),{item:o.item,operationID:o.operationID},null,8,["item","operationID"])]),_:1})]),_:1})]),_:1},8,["model-value"])]))}};export{N as default};