import{i as O,u as P,d as F,_ as G,o as H,r as k,c as u,h as r,e as c,f as n,w as s,n as m,z as _,A as p,C as f,m as v,q as R,aD as y,be as W,F as q,t as X,l,j as g,Q as J,aE as K,v as Y,ah as Z,aX as S,G as ee}from"./app.10579c01.js";import{Q as te}from"./QBadge.6984755e.js";import{Q as ae}from"./QChatMessage.057585fe.js";const ne=g("div",{class:"text-h6"},[_("Notes for Dscan "),g("span")],-1),se={key:0},le={key:1},ce={__name:"DscanMessage",setup(oe){O("can");let o=P();const V=F(()=>G(()=>import("./index.600cd088.js"),["assets/index.600cd088.js","assets/app.10579c01.js","assets/app.4fce15f2.css"]));H(async()=>{});let i=k(),d=k(!1);const M=async a=>{await S({method:"delete",url:"/api/dscanmessage/"+a,withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}})},Q=async()=>{const a={message:i.value};await S({method:"put",url:"/api/dscanmessage/"+o.dScan.id,withCredentials:!0,data:a,headers:{Accept:"application/json","Content-Type":"application/json"}}),i.value=null};let T=async()=>{o.dScanChatWindowId=o.dScan.id},h=async()=>{i.value=null,o.dScanChatWindowId=null},B=a=>new Date(a).getTime(),$=a=>{const t=new Date(a);return new Date-t<18e5},j=u(()=>D.value.length),x=u(()=>o.getDscanUnreadMessageCount(o.dScan.id)),A=u(()=>j.value?"fa-solid fa-message":"fa-regular fa-message"),w=a=>o.user_id==a,I=(a,t)=>o.user_id==t?"me":a,N=a=>o.user_id==a?"amber-7":"positive",U=u(()=>!i.value),D=u(()=>o.dScanMessages),E=a=>"https://image.eveonline.com/Character/"+a.user.eve_user_id+"_128.jpg";return(a,t)=>(r(),c("div",null,[n(v,{"text-color":"positive",icon:A.value,padding:"none",round:"",flat:"",onClick:t[0]||(t[0]=e=>d.value=!0)},{default:s(()=>[x.value&&!d.value?(r(),m(te,{key:0,color:"red",floating:""},{default:s(()=>[_(p(x.value),1)]),_:1})):f("",!0)]),_:1},8,["icon"]),n(Z,{modelValue:d.value,"onUpdate:modelValue":t[4]||(t[4]=e=>d.value=e),onBeforeShow:t[5]||(t[5]=e=>l(T)()),onBeforeHide:t[6]||(t[6]=e=>l(h)())},{default:s(()=>[n(R,{class:"my-card myRoundTop",style:{width:"1000px","max-height":"900px",height:"900px"}},{default:s(()=>[n(y,{class:"bg-primary myCardHeader text-center"},{default:s(()=>[ne]),_:1}),n(y,{id:"messages",class:"overflow-auto",style:{height:"600px"}},{default:s(()=>[n(W,{"enter-active-class":"animate__animated animate__zoomIn"},{default:s(()=>[(r(!0),c(q,null,X(D.value,(e,L)=>(r(),m(ae,{key:`${e.id}-message`,text:[e.message],name:l(I)(e.user.name,e.user_id),avatar:l(E)(e),sent:l(w)(e.user_id),"bg-color":l(N)(e.user_id)},{stamp:s(()=>[_("Sent: "),(r(),m(l(V),{key:`${e.id}-time`,interval:6e4,time:l(B)(e.created_at)},{default:s(({days:C,hours:b,minutes:z,seconds:re})=>[C!="00"?(r(),c("span",se,p(C)+"days, ",1)):f("",!0),b!="00"?(r(),c("span",le,p(b)+"hours,",1)):f("",!0),g("span",null,p(z)+"minutes",1),_(" ago ")]),_:2},1032,["time"])),l($)(e.created_at)&&l(w)(e.user_id)?(r(),m(v,{key:0,color:"negative",padding:"none",icon:"fa-solid fa-trash-can",flat:"",size:"xs",rounded:"",onClick:C=>M(e.id)},null,8,["onClick"])):f("",!0)]),_:2},1032,["text","name","avatar","sent","bg-color"]))),128))]),_:1})]),_:1}),n(y,null,{default:s(()=>[g("div",null,[n(J,{"input-style":"height: 150px",modelValue:i.value,"onUpdate:modelValue":t[1]||(t[1]=e=>i.value=e),clearable:"",outlined:"",rounded:"",dense:"",type:"textarea",label:"Message"},null,8,["modelValue"])])]),_:1}),n(K,{align:"between"},{default:s(()=>[n(v,{rounded:"",label:"Submit",color:"primary",disable:U.value,onClick:t[2]||(t[2]=e=>Q())},null,8,["disable"]),Y(n(v,{rounded:"",label:"Close",color:"negative",onClick:t[3]||(t[3]=e=>l(h)())},null,512),[[ee]])]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{ce as default};
