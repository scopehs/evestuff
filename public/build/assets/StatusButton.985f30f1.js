import{r as w,c as h,h as s,e as o,f as i,w as e,p as y,s as d,F as x,t as k,v as g,n as C,y as b,z as B,A as Q,l as r,x as j,m as D,G as S}from"./app.10579c01.js";const A={class:"row justify-center"},U={__name:"StatusButton",props:{item:Object},setup(n){const t=n;let c=async a=>{var l=null;l={station_status_id:a},await axios({method:"put",url:"/api/stationsheet/updatestationnotification/"+t.item.id,data:l,withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}})},p=w([{title:"New",value:1},{title:"Online",value:16},{title:"Armor",value:8},{title:"Hull",value:9},{title:"Destroyed",value:7},{title:"Unknown",value:18}]),m=h(()=>p.value.filter(a=>a.value!=t.item.status.id)),f=()=>t.item.status.id==8?"warning":t.item.status.id==9?"deep-orange-13":t.item.status.id==18?"blue-grey-8":t.item.status.id==16?"positive":t.item.status.id==7?"negative":"primary",v=()=>t.item.status.name.replace("Upcoming - ","").replace("Reffed - ","");return(a,l)=>(s(),o("div",A,[i(D,{color:r(f)(),label:r(v)(n.item),rounded:""},{default:e(()=>[i(y,null,{default:e(()=>[i(d,{style:{"min-width":"100px"}},{default:e(()=>[i(d,{style:{"min-width":"100px"}},{default:e(()=>[(s(!0),o(x,null,k(m.value,(u,_)=>g((s(),C(j,{clickable:"",key:_,onClick:F=>r(c)(u.value)},{default:e(()=>[i(b,null,{default:e(()=>[B(Q(u.title),1)]),_:2},1024)]),_:2},1032,["onClick"])),[[S]])),128))]),_:1})]),_:1})]),_:1})]),_:1},8,["color","label"])]))}};export{U as default};