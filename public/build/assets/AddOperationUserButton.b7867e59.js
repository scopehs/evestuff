import{u as $,r as v,c,h as m,e as x,n as b,m as f,C as y,f as t,w as d,q as O,aD as w,j as n,A as q,Q as k,aE as P,v as Q,l as _,ah as H,G as g}from"./app.10579c01.js";import{Q as L}from"./QSelect.32be28f9.js";import{Q as S}from"./QSlideTransition.6d4d03a3.js";import{Q as T}from"./QRadio.4f3e8d58.js";import"./QItemLabel.1f7b55df.js";import"./use-checkbox.45f82c4c.js";const z={class:"no-margin"},F={class:"colum q-gutter-lg"},G={class:"col"},M={class:"col"},J={key:0,class:"col"},K={key:0,class:"col"},W={class:"colum"},X=n("div",{class:"col flex flex-center"},[n("span",{class:"text-caption"}," Entosis Link")],-1),Y={class:"col"},ue={__name:"AddOperationUserButton",props:{operationID:Number,type:{type:Number,default:1},char:{type:Object,required:!1}},setup(B){const l=B;let h=$(),i=v(!1),D=v([{text:"Hacker",value:1},{text:"Support",value:5},{text:"Scout",value:2},{text:"FC",value:3},{text:"Command",value:4}]),s=v(),o=v(),r=v(),u=v(),C=c(()=>!!(o.value&&o.value.value==1)),V=()=>{i.value=!1,s.value=null,o.value=null,r.value=null,u.value=null},U=c(()=>{if(C.value){if(s.value&&o.value&&r.value&&u.value)return!0}else if(s.value&&o.value)return!0;return!1}),A=async()=>{l.type==1?await N():await j(),V()},N=async()=>{var p={user_id:h.user_id,operation_id:l.operationID,name:s.value,entosis:u.value,ship:r.value,role_id:o.value.value,user_status_id:1};await axios({method:"POST",url:"/api/newcampaignusers/"+l.operationID+"/"+h.user_id,withCredentials:!0,data:p,headers:{Accept:"application/json","Content-Type":"application/json"}})},j=async()=>{var p={user_id:h.user_id,operation_id:l.operationID,name:s.value,user_status_id:1,role_id:o.value.value,entosis:o.value.value!=1?null:u.value,ship:o.value.value!=1?null:r.value};await axios({method:"PUT",url:"/api/newcampaignusers/"+l.char.id+"/"+l.operationID,withCredentials:!0,data:p,headers:{Accept:"application/json","Content-Type":"application/json"}})},I=()=>{if(l.type==2){let p=l.char.userrole.id,e=l.char.userrole.role;s.value=l.char.name,o.value={value:p,text:e},r.value=l.char.ship,u.value=l.char.entosis}},E=c(()=>l.type==1?"Add Character":"Edit Character"),R=c(()=>l.type==1?"Add":"Update");return(p,e)=>(m(),x("div",null,[l.type==1?(m(),b(f,{key:0,color:"positive",icon:"fa-solid fa-plus",flat:"",label:"Char",onClick:e[0]||(e[0]=a=>i.value=!i.value)})):y("",!0),l.type==2?(m(),b(f,{key:1,color:"warning",icon:"fa-solid fa-edit",flat:"",round:"",padding:"none",size:"xs",onClick:e[1]||(e[1]=a=>i.value=!i.value)})):y("",!0),t(H,{onBeforeShow:e[8]||(e[8]=a=>_(I)()),onBeforeHide:e[9]||(e[9]=a=>_(V)()),class:"myRoundTop",modelValue:i.value,"onUpdate:modelValue":e[10]||(e[10]=a=>i.value=a),persistent:""},{default:d(()=>[t(O,{class:"myRoundTop",flat:""},{default:d(()=>[t(w,{class:"bg-primary text-center"},{default:d(()=>[n("h5",z,q(E.value),1)]),_:1}),t(w,null,{default:d(()=>[n("div",F,[n("div",G,[t(k,{outlined:"",rounded:"",modelValue:s.value,"onUpdate:modelValue":e[2]||(e[2]=a=>s.value=a),type:"text",label:"Name"},null,8,["modelValue"])]),n("div",M,[t(L,{"option-label":"text","option-value":"value",outlined:"",rounded:"",modelValue:o.value,"onUpdate:modelValue":e[3]||(e[3]=a=>o.value=a),options:D.value,label:"Role"},null,8,["modelValue","options"])]),t(S,null,{default:d(()=>[C.value?(m(),x("div",J,[t(k,{outlined:"",rounded:"",modelValue:r.value,"onUpdate:modelValue":e[4]||(e[4]=a=>r.value=a),type:"text",label:"Ship"},null,8,["modelValue"])])):y("",!0)]),_:1}),t(S,null,{default:d(()=>[C.value?(m(),x("div",K,[n("div",W,[X,n("div",Y,[t(T,{modelValue:u.value,"onUpdate:modelValue":e[5]||(e[5]=a=>u.value=a),val:1,label:"Tech 1"},null,8,["modelValue"]),t(T,{modelValue:u.value,"onUpdate:modelValue":e[6]||(e[6]=a=>u.value=a),val:2,label:"Tech 2"},null,8,["modelValue"])])])])):y("",!0)]),_:1})])]),_:1}),t(P,{align:"center"},{default:d(()=>[Q(t(f,{rounded:"",color:"positive",disable:!U.value,label:R.value,onClick:e[7]||(e[7]=a=>_(A)())},null,8,["disable","label"]),[[g]]),Q(t(f,{rounded:"",color:"negative",label:"Close"},null,512),[[g]])]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{ue as default};
