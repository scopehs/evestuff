import{d as u,_ as f,r as y,u as k,o as T,b as B,c as D,h as o,e as n,j as c,f as l,w as s,l as i,n as S,A as a,C as P}from"./app.10579c01.js";import{Q as w}from"./QTd.f19052d1.js";import{Q as V}from"./QTable.4d294d9e.js";import"./QSeparator.0566634d.js";import"./QMarkupTable.d2f16f87.js";import"./QSelect.32be28f9.js";import"./QItemLabel.1f7b55df.js";import"./QLinearProgress.84b4c331.js";import"./QCheckbox.bf3f01de.js";import"./use-checkbox.45f82c4c.js";const q={class:""},C={class:"row justify-center"},E={class:"col"},R=c("div",{class:"row full-width flex-center q-pt-xs myRoundTop bg-primary"},[c("span",{class:"text-h4"},"Watched Systems")],-1),z={key:0,class:"text-red"},A={key:1,class:"text-red"},I={key:2,class:"text-primary"},H={__name:"OperationInfoSystemWatchTable",setup(Q){const g=u(()=>f(()=>import("./StagingDscan.221763fd.js"),["assets/StagingDscan.221763fd.js","assets/app.10579c01.js","assets/app.4fce15f2.css"])),h=u(()=>f(()=>import("./index.600cd088.js"),["assets/index.600cd088.js","assets/app.10579c01.js","assets/app.4fce15f2.css"]));let b=y({sortBy:"progress",descending:!1,page:1,rowsPerPage:0}),m=k();T(async()=>{}),B(()=>{});let x=e=>e?Date.parse(e):0,v=y([{name:"system",required:!0,align:"left",style:"font-size: 0.8rem!important;",label:"System",classes:"text-no-wrap",field:e=>e.system_name,format:e=>`${e}`,sortable:!1,filter:!0},{name:"dscan",required:!0,align:"center",label:"Dscan",style:"font-size: 0.8rem!important;",classes:"text-no-wrap",field:e=>e.dscan,format:e=>`${e}`,sortable:!1,filter:!0},{name:"age",required:!0,align:"center",label:"Dscan",style:"font-size: 0.8rem!important;",classes:"text-no-wrap",field:e=>e.dscan.updated_at?e.dscan.updated_at:null,format:e=>`${e}`,sortable:!1,filter:!0}]);return D(()=>{let e=30;return m.size.height-e+"px"}),(e,p)=>(o(),n("div",q,[c("div",C,[c("div",E,[l(V,{class:"myTableWatchedSystem myRound bg-webBack",rows:i(m).operationInfoPage.watch_systems,columns:v.value,"table-class":" text-webway","table-header-class":" text-weight-bolder","row-key":"id",dark:"",dense:"","hide-pagination":"",hide:"",ref:"tableRef",rounded:"",pagination:b.value},{top:s(t=>[R]),"body-cell-dscan":s(t=>[l(w,{props:t},{default:s(()=>[l(i(g),{item:t.row},null,8,["item"])]),_:2},1032,["props"])]),"body-cell-age":s(t=>[l(w,{props:t},{default:s(()=>[t.row.dscan&&t.row.dscan.updated_at?(o(),S(i(h),{key:0,class:"q-pl-sm","emit-events":!1,time:i(x)(t.row.dscan.updated_at),interval:1e3},{default:s(({hours:_,minutes:r,seconds:d})=>[_>=1?(o(),n("span",z,a(_)+":"+a(r)+":"+a(d),1)):r>=20?(o(),n("span",A,a(r)+":"+a(d),1)):(o(),n("span",I,a(r)+":"+a(d),1))]),_:2},1032,["time"])):P("",!0)]),_:2},1032,["props"])]),_:1},8,["rows","columns","pagination"])])])]))}};export{H as default};