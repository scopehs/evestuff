import{a as h,r as w,n as C,w as t,q as y,h as v,f as e,m as r,l as i,aD as n,Q as b}from"./app.10579c01.js";const x={__name:"FeedBacknew",setup(k){let s=h(),p=async()=>{await axios({method:"get",url:"api/reconpullregion",withCredentials:!0,data:this.rcdata,headers:{Accept:"application/json","Content-Type":"application/json"}})},u=w(),d=()=>{let a=s.resolve({path:"/hithere"});window.open(a.href)},m=async()=>{await axios({method:"post",url:"api/rcInput",withCredentials:!0,data:this.rcdata,headers:{Accept:"application/json","Content-Type":"application/json"}})},c=()=>{let a=s.resolve({path:"/hitherealso"});window.open(a.href)},f=()=>{let a=s.resolve({path:"/hithereagain"});window.open(a.href)};return(a,l)=>(v(),C(y,{class:"my-card"},{default:t(()=>[e(n,null,{default:t(()=>[e(r,{color:"primary",label:"Recon Pull Test",onClick:l[0]||(l[0]=o=>i(p)())})]),_:1}),e(n,null,{default:t(()=>[e(r,{color:"primary",label:"ADD",href:"/esi/add"})]),_:1}),e(n,null,{default:t(()=>[e(r,{color:"primary",label:"Prequal",onClick:l[1]||(l[1]=o=>i(d)())})]),_:1}),e(n,null,{default:t(()=>[e(r,{color:"primary",label:"Horizon",onClick:l[2]||(l[2]=o=>i(c)())})]),_:1}),e(n,null,{default:t(()=>[e(r,{color:"primary",label:"Logs",onClick:l[3]||(l[3]=o=>i(f)())})]),_:1}),e(n,null,{default:t(()=>[e(b,{modelValue:u.value,"onUpdate:modelValue":l[4]||(l[4]=o=>u.value=o),type:"text",label:"RCdata"},null,8,["modelValue"]),e(r,{color:"primary",label:"Submit",onClick:l[5]||(l[5]=o=>i(m)())})]),_:1})]),_:1}))}};export{x as default};
