import{d as w,_ as b,r as q,c as m,h as d,e as f,j as F,n as v,w as l,l as _,A as i,f as r,q as x,aD as U,Q as S,aE as D,v as T,m as p,p as I,B as Y,C as g,z as V,G as C}from"./app.10579c01.js";import{a as G}from"./QSelect.32be28f9.js";import"./QItemLabel.1f7b55df.js";const Z={class:"row"},A={class:"col"},J={key:0},K={key:0},te={__name:"NewSystemTableTimer",props:{node:Object,operationID:Number,extra:{type:Number,default:1},tidiProp:Number,systemIDProp:Number},setup(h){const e=h,O=w(()=>b(()=>import("./index.600cd088.js"),["assets/index.600cd088.js","assets/app.10579c01.js","assets/app.4fce15f2.css"])),P=w(()=>b(()=>import("./index.96e5e95a.js"),["assets/index.96e5e95a.js","assets/app.10579c01.js","assets/app.4fce15f2.css"]));let c=q(null),N=m(()=>!(e.node.node_status.id==7||e.node.node_status.id==8)),y=async()=>{var n=parseInt(c.value.substr(0,2)),t=parseInt(c.value.substr(3,2)),s=n*60+t;if(t=n*60+t,e.extra==2)var t=t/(e.tidiProp/100);else var t=t/(e.node.system.tidi/100);var a=new Date(Date.now()+t*1e3).toISOString().replace("T"," ").substr(0,19);if(e.node.node_status.id==7||e.node.node_status.id==8||e.node.node_status.id==9){var o={end_time:a,input_time:new Date().toISOString().replace("T"," ").substr(0,19),base_time:s,system_id:e.node.system_id};await axios({method:"put",url:"/api/addtimertonode/"+e.node.id,withCredentials:!0,data:o,headers:{Accept:"application/json","Content-Type":"application/json"}})}else{if(e.extra==1)var k=u.value.id,o={end_time:a,input_time:new Date().toISOString().replace("T"," ").substr(0,19),base_time:s,system_id:e.node.system_id};else var k=e.node.id,o={end_time:a,input_time:new Date().toISOString().replace("T"," ").substr(0,19),base_time:s,system_id:e.systemIDProp};await axios({method:"put",url:"/api/addprimetimer/"+k,withCredentials:!0,data:o,headers:{Accept:"application/json","Content-Type":"application/json"}})}},u=m(()=>e.extra==1&&e.node.prime_node_user.length>0?e.node.prime_node_user[0]:null),B=n=>Date.parse(n),E=m(()=>{if(e.extra==1)if(e.node.node_status.id==2){var n=new Date(u.value.updated_at);return n.getUTCFullYear()+"-"+("0"+(n.getUTCMonth()+1)).slice(-2)+"-"+("0"+n.getUTCDate()).slice(-2)+" "+("0"+n.getUTCHours()).slice(-2)+":"+("0"+n.getUTCMinutes()).slice(-2)+":"+("0"+n.getUTCSeconds()).slice(-2)}else{var t=new Date(e.node.created_at);return t.getUTCFullYear()+"-"+("0"+(t.getUTCMonth()+1)).slice(-2)+"-"+("0"+t.getUTCDate()).slice(-2)+" "+("0"+t.getUTCHours()).slice(-2)+":"+("0"+t.getUTCMinutes()).slice(-2)+":"+("0"+t.getUTCSeconds()).slice(-2)}else{var s=new Date(e.node.created_at);return s.toISOString()}}),H=m(()=>{if(e.extra==1)if(e.node.prime_node_user.length>0)var n=e.node.prime_node_user[0].node_status.id;else var n=e.node.node_status.id;else var n=e.node.node_status.id;switch(n){case 1:return!0;case 2:return!0;case 3:return!1;case 4:return!1;case 5:return!1;case 6:return!0;case 7:return!1;case 8:return!1;case 9:return!1;case 10:return!0}}),M=(n,t,s)=>(t<5||n>0)&&e.node.node_status.id==1?!0:(t<10||n>0)&&e.node.node_status.id==2,Q=m(()=>u.value?u.value.end_time==null&&u.value.node_status.id==3?!0:u.value.end_time==null&&(e.node.node_status.id==7||e.node.node_status.id==8||e.node.node_status.id==9):e.node.node_status.id==3&&e.node.end_time==null?!0:(e.node.node_status.id==7||e.node.node_status.id==8||e.node.node_status.id==9)&&e.node.end_time==null),j=m(()=>{if(e.extra==1)return u.value?u.value.end_time:e.node.node_status.id==7||e.node.node_status.id==8||e.node.node_status.id==9?e.node.end_time:null;if(e.node.end_time)return e.node.end_time}),$=n=>{const t=new Date(n+"Z"),s=Date.now();return t.getTime()-s},z=n=>{const t={};return Object.entries(n).forEach(([s,a])=>{t[s]=a<10?`0${a}`:String(a)}),t},L=m(()=>e.node.node_status.id==7?"text-white":"text-primary"),R=m(()=>{if(e.extra==1)if(e.node.prime_node_user.length>0)var n=u.value.node_status.id;else var n=e.node.node_status.id;else var n=e.node.node_status.id;return n==7||n==8||n==3||n==9});return(n,t)=>(d(),f("div",Z,[F("div",A,[H.value?(d(),v(_(O),{key:0,time:_(B)(E.value),"emit-events":!1,interval:1e3},{default:l(({hours:s,minutes:a,seconds:o})=>[_(M)(s,s,o)?(d(),f("div",{key:`${e.node.id}-1-timer-age`,class:"text-positive"},i(s)+":"+i(a)+":"+i(o),1)):(d(),f("div",{key:`${e.node.id}-2-timer-age`,class:"text-negative"},i(s)+":"+i(a)+":"+i(o),1))]),_:1},8,["time"])):Q.value?(d(),v(p,{key:1,color:"warning",outline:N.value,label:"Add time",rounded:"",class:"myOutLineButton"},{default:l(()=>[r(I,null,{default:l(()=>[r(x,{class:"my-card"},{default:l(()=>[r(U,null,{default:l(()=>[r(S,{modelValue:c.value,"onUpdate:modelValue":t[0]||(t[0]=s=>c.value=s),type:"text",label:"Hack Time mm:ss",mask:"##:##",autofocus:"",rounded:"",outlined:""},null,8,["modelValue"])]),_:1}),r(D,{align:"center"},{default:l(()=>[T(r(p,{color:"positive",rounded:"",label:"Add",onClick:t[1]||(t[1]=s=>_(y)())},null,512),[[C]]),T(r(p,{color:"negative",rounded:"",label:"Close"},null,512),[[C]])]),_:1})]),_:1})]),_:1})]),_:1},8,["outline"])):(d(),v(_(P),{key:2,time:_($)(j.value),interval:1e3,"emit-events":!1,transform:_(z)},{default:l(({hours:s,minutes:a,seconds:o})=>[e.node.node_status.id!=8?(d(),f("span",{key:0,class:Y(L.value)},[s>0?(d(),f("span",J,i(s)+":",1)):g("",!0),V(i(a)+":"+i(o),1)],2)):(d(),v(G,{key:1,color:"blue-14"},{default:l(()=>[s>0?(d(),f("span",K,i(s)+":",1)):g("",!0),V(i(a)+":"+i(o),1)]),_:2},1024))]),_:1},8,["time","transform"])),R.value?(d(),v(p,{key:3,color:"warning",icon:"fa-solid fa-edit",round:"",flat:"",size:"xs"},{default:l(()=>[r(I,{onBeforeHide:t[4]||(t[4]=s=>c.value=null)},{default:l(()=>[r(x,{class:"my-card"},{default:l(()=>[r(U,null,{default:l(()=>[r(S,{modelValue:c.value,"onUpdate:modelValue":t[2]||(t[2]=s=>c.value=s),type:"text",label:"Hack Time mm:ss",mask:"##:##",outlined:"",rounded:""},null,8,["modelValue"])]),_:1}),r(D,{align:"center"},{default:l(()=>[T(r(p,{rounded:"",color:"positive",label:"update",onClick:t[3]||(t[3]=s=>_(y)())},null,512),[[C]]),T(r(p,{rounded:"",color:"negative",label:"close"},null,512),[[C]])]),_:1})]),_:1})]),_:1})]),_:1})):g("",!0)])]))}};export{te as default};
