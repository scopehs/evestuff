import{r as d,c as u,h as s,e as v,f as i,w as n,p as k,s as w,F as I,t as b,v as C,n as S,y as B,z as N,A as H,l as Q,x as F,m as P,G as j}from"./app.10579c01.js";const T={__name:"NewSystemTableStatusButton",props:{node:Object,operationID:Number,extra:{type:Number,default:1}},setup(p){const e=p;let o=d([{title:"Warm up",value:2},{title:"Hacking",value:3},{title:"Friendly Hacking",value:8},{title:"Passive",value:9},{title:"Success",value:4},{title:"Pushed off",value:6},{title:"Hostile Hacking",value:7},{title:"Failed",value:5}]),f=d([{title:"Warm up",value:2},{title:"Hacking",value:3},{title:"Pushed off",value:6}]),t=u(()=>e.extra==2?e.node.node_status.id:e.node.prime_node_user.length>0?e.node.prime_node_user[0].node_status.id:e.node.node_status.id),_=u(()=>!(t.value==7||t.value==9||t.value==8)),m=u(()=>e.extra==2?e.node.node_status.name:e.node.prime_node_user.length>0?e.node.prime_node_user[0].node_status.name:e.node.node_status.name),c=u(()=>{if(t.value==1)return"deep-orange-6";if(t.value==2)return"lime-10";if(t.value==3)return"green-9";if(t.value==8)return"blue-10";if(t.value==4)return"green-14";if(t.value==5)return"red-10";if(t.value==6)return"pink-12";if(t.value==7)return"red-6";if(t.value==9)return"grey-6"}),x=u(()=>{if(e.node.prime_node_user.length>0)var r=o.value.filter(a=>a.value==1||a.value==2||a.value==3||a.value==4||a.value==6);else var r=o.value.filter(l=>l.value==1||l.value==8||l.value==9||l.value==4||l.value==6||l.value==7||l.value==5);return r}),y=u(()=>{if(e.extra==1)var r=x.value.filter(a=>a.value!=t.value);else var r=f.value.filter(l=>l.value!=t.value);return r}),g=async r=>{if(e.extra==1)if(e.node.prime_node_user.length>0)var a={status_id:r.value,system_id:e.node.system_id,opID:e.operationID,extra:!1,prime:!0};else var a={status_id:r.value,system_id:e.node.system_id,opID:e.operationID,extra:!1,prime:!1};else var a={status_id:r.value,system_id:e.node.op_user.system_id,opID:e.operationID,extra:!0,prime:!1};await axios({method:"post",url:"/api/updatenodestats/"+h.value,withCredentials:!0,data:a,headers:{Accept:"application/json","Content-Type":"application/json"}})},h=u(()=>e.extra==2?e.node.id:e.node.prime_node_user.length>0?e.node.prime_node_user[0].id:e.node.id);return(r,a)=>(s(),v("div",null,[i(P,{color:c.value,rounded:"",class:"myOutLineButton",label:m.value,outline:_.value},{default:n(()=>[i(k,null,{default:n(()=>[i(w,{style:{"min-width":"100px"}},{default:n(()=>[(s(!0),v(I,null,b(y.value,(l,D)=>C((s(),S(F,{clickable:"",key:D,onClick:L=>Q(g)(l)},{default:n(()=>[i(B,null,{default:n(()=>[N(H(l.title),1)]),_:2},1024)]),_:2},1032,["onClick"])),[[j]])),128))]),_:1})]),_:1})]),_:1},8,["color","label","outline"])]))}};export{T as default};