import{u as C,d as f,_ as y,h as s,e as i,j as c,f as l,l as r,m as B,z as g,A as e,w as _,aP as x,C as w}from"./app.10579c01.js";const T={class:"row"},V={class:"col-9"},N={class:"row"},S={key:0,class:"col"},A={__name:"SystemCheckButton",props:{item:Object,operationID:Number},setup(d){const t=d;let p=C();const u=f(()=>y(()=>import("./index.600cd088.js"),["assets/index.600cd088.js","assets/app.10579c01.js","assets/app.4fce15f2.css"]));let h=async()=>{var a={user_id:p.user_id};await axios({method:"POST",url:"/api/checkedat/"+t.item.id,data:a,withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}})},k=a=>Date.parse(a);return(a,v)=>(s(),i("div",null,[c("div",T,[c("div",V,[l(B,{color:"positive",icon:"fa-solid fa-magnifying-glass-location",label:"System Checked",onClick:r(h),rounded:"",class:"myOutLineButton"},null,8,["onClick"])])]),c("div",N,[t.item.check_user?(s(),i("div",S,[g(" Check By "+e(t.item.check_user.name)+" ",1),l(r(u),{time:r(k)(t.item.scouted_at),interval:1e3},{default:_(({hours:o,minutes:n,seconds:m})=>[l(x,{name:"custom-classes","enter-active-class":"animate__animated animate__heartBeat animate__repeat-2","leave-active-class":"animate__animated animate__flash",mode:"out-in"},{default:_(()=>[o==0&&n<5?(s(),i("span",{key:`${t.item.id}-1-timer-age`,class:"text-positive"},e(o)+":"+e(n)+":"+e(m),1)):(s(),i("span",{key:`${t.item.id}-2-timer-age`,class:"text-negative"},e(o)+":"+e(n)+":"+e(m),1))]),_:2},1024)]),_:1},8,["time"])])):w("",!0)])]))}};export{A as default};
