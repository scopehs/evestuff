import{u as N,i as $,r as w,o as E,b as D,c as m,e as f,j as n,f as o,w as l,q as F,g as M,h as i,Q as z,k as G,z as x,A as Q,F as h,t as y,n as c,l as d,p as H,s as J,v as K,x as O,y as W,m as k,C,aD as g,G as X,H as Y,I as Z}from"./app.10579c01.js";import{Q as I}from"./QTd.f19052d1.js";import{a as R}from"./QSelect.32be28f9.js";import{Q as ee}from"./QTr.00779787.js";import{Q as te}from"./QTable.4d294d9e.js";import{Q as se}from"./QSlideTransition.6d4d03a3.js";import{_ as ae}from"./_plugin-vue_export-helper.cdc0426e.js";import"./QItemLabel.1f7b55df.js";import"./QSeparator.0566634d.js";import"./QMarkupTable.d2f16f87.js";import"./QLinearProgress.84b4c331.js";import"./QCheckbox.bf3f01de.js";import"./use-checkbox.45f82c4c.js";const U=p=>(Y("data-v-4be6c2f2"),p=p(),Z(),p),le={class:"q-ma-md"},re={class:"row justify-between"},oe={class:"col-8"},ne=U(()=>n("div",{class:"row full-width flex-center q-pt-xs myRoundTop bg-primary"},[n("div",{class:"col-12 flex flex-center"},[n("span",{class:"text-h4"},"Users")])],-1)),ie={class:"row full-width q-pt-md justify-between"},de={class:"col-auto"},ue={class:"row q-gutter-sm q-pl-md"},ce={class:"col-3"},pe=U(()=>n("h4",{class:"no-margin"},"Filter By Roles",-1)),me={__name:"AdminPanel",setup(p){M(e=>({"7e8ea950":V.value}));let a=N(),_=$("can"),v=w();E(async()=>{Echo.private("userupdate").listen("UserUpdate",e=>{this.refresh}),_("view_admin_logs")&&await a.getLoggingAdmin(),await a.getUsers(),await a.getRoles()}),D(async()=>{Echo.leave("userupdate")});let L=w({sortBy:"name",descending:!1,page:1,rowsPerPage:50}),b=m(()=>{let e=0;for(let s=0;s<a.rolesList.length;s++)a.rolesList[s].selected===!0&&e++;return e}),S=()=>{for(let e=0;e<a.rolesList.length;e++)a.rolesList[e].selected=!1};m(()=>a.rolesList.filter(e=>e.selected==!0));let q=m(()=>b.value?a.users.filter(s=>{const t=s.roles.map(r=>r.id);return a.rolesList.some(r=>r.selected&&t.includes(r.value))}):a.users),A=async(e,s)=>{var t={roleId:s,userId:e};await axios({method:"put",url:"/api/rolesremove",withCredentials:!0,data:t,headers:{Accept:"application/json","Content-Type":"application/json"}}),a.getUsers()},T=async(e,s)=>{var t={roleId:e,userId:s};await axios({method:"put",url:"/api/rolesadd",withCredentials:!0,data:t,headers:{Accept:"application/json","Content-Type":"application/json"}}),a.getUsers()},B=w([{name:"name",align:"left",required:!1,label:"Name",classes:"text-no-wrap",field:e=>e.name,format:e=>`${e}`,sortable:!1},{name:"roles",required:!0,align:"center",label:"Roles",classes:"text-no-wrap",style:"width: 80%",field:e=>e.id,format:e=>`${e}`,sortable:!1,filter:!0}]),j=e=>e.filter(s=>s.name!="Super Admin"),V=m(()=>{let e=30;return a.size.height-e+"px"});return(e,s)=>(i(),f("div",le,[n("div",re,[n("div",oe,[o(te,{title:"Connections",class:"myTableUsers myRound bg-webBack",rows:q.value,columns:B.value,"table-class":" text-webway","table-header-class":" text-weight-bolder","row-key":"id",dark:"",dense:"",filter:v.value,ref:"tableRef",rounded:"",pagination:L.value},{top:l(t=>[ne,n("div",ie,[n("div",de,[n("div",ue,[o(z,{rounded:"",standout:"",dense:"",debounce:"300",modelValue:v.value,"onUpdate:modelValue":s[0]||(s[0]=r=>v.value=r),clearable:"",placeholder:"Search"},{append:l(()=>[o(G,{name:"fa-solid fa-magnifying-glass"})]),_:1},8,["modelValue"])])])])]),body:l(t=>[o(ee,{props:t},{default:l(()=>[o(I,{key:"name",props:t},{default:l(()=>[x(Q(t.row.name),1)]),_:2},1032,["props"]),o(I,{key:"roles",props:t},{default:l(()=>[(i(!0),f(h,null,y(d(j)(t.row.roles),(r,u)=>(i(),c(R,{key:u,removable:d(_)("super"),onRemove:P=>d(A)(t.row.id,r.id),color:"primary",label:r.name},null,8,["removable","onRemove","label"]))),128)),d(_)("super")?(i(),c(k,{key:0,round:"",color:"green",flat:"",icon:"fa-solid fa-plus"},{default:l(()=>[o(H,null,{default:l(()=>[o(J,{style:{"min-width":"100px"}},{default:l(()=>[(i(!0),f(h,null,y(d(a).rolesList,(r,u)=>K((i(),c(O,{key:u,clickable:""},{default:l(()=>[o(W,{onClick:P=>d(T)(r.value,t.row.id)},{default:l(()=>[x(Q(r.text),1)]),_:2},1032,["onClick"])]),_:2},1024)),[[X]])),128))]),_:2},1024)]),_:2},1024)]),_:2},1024)):C("",!0)]),_:2},1032,["props"])]),_:2},1032,["props"])]),_:1},8,["rows","columns","filter","pagination"])]),n("div",ce,[o(F,{class:"myRoundTop"},{default:l(()=>[o(g,{class:"bg-primary text-h5 text-center"},{default:l(()=>[pe]),_:1}),o(g,null,{default:l(()=>[(i(!0),f(h,null,y(d(a).rolesList,(t,r)=>(i(),c(R,{key:r,color:"primary",outline:!t.selected,selected:t.selected,"onUpdate:selected":u=>t.selected=u,label:t.text},null,8,["outline","selected","onUpdate:selected","label"]))),128))]),_:1}),o(se,null,{default:l(()=>[b.value?(i(),c(g,{key:0},{default:l(()=>[o(k,{color:"primary",label:"Clear",onClick:s[1]||(s[1]=t=>d(S)())})]),_:1})):C("",!0)]),_:1})]),_:1})])])]))}},Re=ae(me,[["__scopeId","data-v-4be6c2f2"]]);export{Re as default};