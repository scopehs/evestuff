import{i as L,u as z,d as F,_ as G,o as H,r as b,c as u,h as r,e as m,f as s,w as l,n as f,z as _,A as d,C as c,m as g,q as R,aD as C,j as y,l as n,be as W,F as q,t as X,Q as J,aE as K,v as Y,ah as Z,aX as k,G as ee}from"./app.10579c01.js";import{Q as te}from"./QBadge.6984755e.js";import{Q as ae}from"./QChatMessage.057585fe.js";const ne={class:"text-h6"},oe={key:0},se={key:1},pe={__name:"OperationInfoMessageCard",setup(le){L("can");let o=z();const V=F(()=>G(()=>import("./index.600cd088.js"),["assets/index.600cd088.js","assets/app.10579c01.js","assets/app.4fce15f2.css"]));H(async()=>{});let i=b(),p=b(!1);const D=async a=>{await k({method:"delete",url:"/api/operationinfopagemessage/"+a,withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}})},M=async()=>{const a={message:i.value};await k({method:"put",url:"/api/operationinfopagemessage/"+o.operationInfoPage.id,withCredentials:!0,data:a,headers:{Accept:"application/json","Content-Type":"application/json"}}),i.value=null};let Q=async()=>{o.operationInfoChatWindow=o.operationInfoPage.id},h=async()=>{i.value=null,o.operationInfoChatWindow=null},T=a=>new Date(a).getTime(),B=a=>{const t=new Date(a);return new Date-t<18e5},O=u(()=>o.operationInfoPage.messages.length),x=u(()=>o.getOperationInfoUnreadMessageCount),P=u(()=>O.value?"fa-solid fa-message":"fa-regular fa-message"),w=a=>o.user_id==a,$=(a,t)=>o.user_id==t?"me":a,j=a=>o.user_id==a?"amber-7":"positive",S=u(()=>!i.value),A=u(()=>o.operationInfoPage.messages),N=a=>"https://image.eveonline.com/Character/"+a.user.eve_user_id+"_128.jpg";return(a,t)=>(r(),m("div",null,[s(g,{"text-color":"positive",icon:P.value,padding:"none",round:"",flat:"",onClick:t[0]||(t[0]=e=>p.value=!0)},{default:l(()=>[x.value&&!p.value?(r(),f(te,{key:0,color:"red",floating:""},{default:l(()=>[_(d(x.value),1)]),_:1})):c("",!0)]),_:1},8,["icon"]),s(Z,{modelValue:p.value,"onUpdate:modelValue":t[4]||(t[4]=e=>p.value=e),onBeforeShow:t[5]||(t[5]=e=>n(Q)()),onBeforeHide:t[6]||(t[6]=e=>n(h)())},{default:l(()=>[s(R,{class:"my-card myRoundTop",style:{width:"1000px","max-height":"900px",height:"900px"}},{default:l(()=>[s(C,{class:"bg-primary myCardHeader text-center"},{default:l(()=>[y("div",ne," Notes for Operation "+d(n(o).operationInfoPage.name),1)]),_:1}),s(C,{id:"messages",class:"overflow-auto",style:{height:"600px"}},{default:l(()=>[s(W,{"enter-active-class":"animate__animated animate__zoomIn"},{default:l(()=>[(r(!0),m(q,null,X(A.value,(e,U)=>(r(),f(ae,{key:`${e.id}-message`,text:[e.message],name:n($)(e.user.name,e.user_id),avatar:n(N)(e),sent:n(w)(e.user_id),"bg-color":n(j)(e.user_id)},{stamp:l(()=>[_("Sent: "),(r(),f(n(V),{key:`${e.id}-time`,interval:6e4,time:n(T)(e.created_at)},{default:l(({days:v,hours:I,minutes:E,seconds:re})=>[v!="00"?(r(),m("span",oe,d(v)+"days, ",1)):c("",!0),I!="00"?(r(),m("span",se,d(I)+"hours,",1)):c("",!0),y("span",null,d(E)+"minutes",1),_(" ago ")]),_:2},1032,["time"])),n(B)(e.created_at)&&n(w)(e.user_id)?(r(),f(g,{key:0,color:"negative",padding:"none",icon:"fa-solid fa-trash-can",flat:"",size:"xs",rounded:"",onClick:v=>D(e.id)},null,8,["onClick"])):c("",!0)]),_:2},1032,["text","name","avatar","sent","bg-color"]))),128))]),_:1})]),_:1}),s(C,null,{default:l(()=>[y("div",null,[s(J,{"input-style":"height: 150px",modelValue:i.value,"onUpdate:modelValue":t[1]||(t[1]=e=>i.value=e),clearable:"",outlined:"",rounded:"",dense:"",type:"textarea",label:"Message"},null,8,["modelValue"])])]),_:1}),s(K,{align:"between"},{default:l(()=>[s(g,{rounded:"",label:"Submit",color:"primary",disable:S.value,onClick:t[2]||(t[2]=e=>M())},null,8,["disable"]),Y(s(g,{rounded:"",label:"Close",color:"negative",onClick:t[3]||(t[3]=e=>n(h)())},null,512),[[ee]])]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{pe as default};