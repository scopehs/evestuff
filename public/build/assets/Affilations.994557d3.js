import{u as q,i as V,r as f,d as v,o as j,b as P,c as w,e as S,f as t,w as i,l as n,g as $,_ as g,h as R,j as s,m as c,A as D,p as L,q as F,aD as M,aE as N,v as h,G as b}from"./app.10579c01.js";import{Q as m}from"./QTd.f19052d1.js";import{Q as z}from"./QSelect.32be28f9.js";import{Q as O}from"./QTable.4d294d9e.js";import"./QItemLabel.1f7b55df.js";import"./QSeparator.0566634d.js";import"./QMarkupTable.d2f16f87.js";import"./QLinearProgress.84b4c331.js";import"./QCheckbox.bf3f01de.js";import"./use-checkbox.45f82c4c.js";const U={class:"q-ma-md"},H={class:"row justify-between full-width flex-center q-pt-xs myRoundTop bg-primary"},I=s("div",{class:"col-auto"},null,-1),G=s("div",{class:"col-auto"},[s("span",{class:"text-h4"},"Affiliations")],-1),J={class:"col-auto"},K={class:"row"},W={class:"row"},X={class:"row"},de={__name:"Affilations",setup(Y){$(e=>({"4e083205":E.value}));let l=q();V("can");let r=f();const _=v(()=>g(()=>import("./AffilationEditPannel.8f3691c1.js"),["assets/AffilationEditPannel.8f3691c1.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QOptionGroup.2acbdfed.js","assets/QRadio.4f3e8d58.js","assets/use-checkbox.45f82c4c.js","assets/QCheckbox.bf3f01de.js","assets/QToggle.a02dc2d0.js"])),x=v(()=>g(()=>import("./AffilationAllianceChips.1acfed13.js"),["assets/AffilationAllianceChips.1acfed13.js","assets/app.10579c01.js","assets/app.4fce15f2.css","assets/QSelect.32be28f9.js","assets/QItemLabel.1f7b55df.js"]));j(async()=>{document.title="Evestuff - Affilations",l.getAffilationTable(),l.getAllianceTickList(),Echo.private("affilation").listen("AffilationUpdate",e=>{e.flag.flag==1&&l.updateAffilation(e.flag.message),e.flag.flag==2&&(l.affiliations=l.affiliations.filter(o=>o.id!=e.flag.message))})}),P(async()=>{Echo.leave("affilation")});let d=f(),u=w(()=>d.value?l.allianceticklist.filter(e=>e.text.toLowerCase().indexOf(d.value)>-1):l.allianceticklist),y=(e,o,a)=>{o(()=>{d.value=e.toLowerCase(),u.value.length>0&&e&&(r.value=u.value[0])})},A=async e=>{await axios({method:"delete",withCredentials:!0,url:"/api/affiliation/"+e,headers:{Accept:"application/json","Content-Type":"application/json"}}),l.affiliations=l.affiliations.filter(o=>o.id!=e)},C=async e=>{let o={alliance_id:r.value.value};await axios({method:"post",data:o,withCredentials:!0,url:"/api/affiliation/alliance/"+e,headers:{Accept:"application/json","Content-Type":"application/json"}}).then(a=>{l.updateAffilation(a.data.affiliation)})},k=()=>{r.value=null,d.value=null},T=f([{name:"name",align:"left",required:!1,label:"Name",classes:"text-no-wrap",field:e=>e.name,format:e=>`${e}`,sortable:!1},{name:"short_name",required:!0,align:"left",label:"Short",classes:"text-no-wrap",field:e=>e.short_name,format:e=>`${e}`,sortable:!1,filter:!0},{name:"color",required:!0,align:"left",label:"Color",classes:"text-no-wrap",field:e=>e.color,format:e=>`${e}`,sortable:!1,filter:!0},{name:"alliances",required:!0,align:"center",label:"Alliances",classes:"text-no-wrap",style:"width: 80%",field:e=>e.id,format:e=>`${e}`,sortable:!1,filter:!0},{name:"actions",required:!0,align:"right",label:"",classes:"text-no-wrap",field:e=>e.id,format:e=>`${e}`,sortable:!1,filter:!0}]),Q=e=>{if(e==0)return"Neutral";if(e==1)return"Red";if(e==2)return"Blue"},B=f({sortBy:"id",descending:!1,page:1,rowsPerPage:0}),E=w(()=>{let e=30;return l.size.height-e+"px"});return(e,o)=>(R(),S("div",U,[t(O,{title:"Connections",class:"myAffilationsTable myRound bg-webBack",rows:n(l).affiliations,columns:T.value,"table-class":" text-webway","table-header-class":" text-weight-bolder","row-key":"id",dark:"",dense:"",ref:"tableRef",rounded:"",pagination:B.value},{top:i(a=>[s("div",H,[I,G,s("div",J,[t(n(_))])])]),"body-cell-actions":i(a=>[t(m,{props:a},{default:i(()=>[s("div",K,[t(n(_),{edit:!0,item:a.row},null,8,["item"]),t(c,{color:"negative",padding:"none",size:"sm",flat:"",round:"",icon:"fa-solid fa-trash-alt",onClick:p=>n(A)(a.row.id)},null,8,["onClick"])])]),_:2},1032,["props"])]),"body-cell-color":i(a=>[t(m,{props:a},{default:i(()=>[s("div",W,D(n(Q)(a.row.color)),1)]),_:2},1032,["props"])]),"body-cell-alliances":i(a=>[t(m,{props:a},{default:i(()=>[s("div",X,[t(n(x),{item:a.row},null,8,["item"]),t(c,{color:"info",padding:"none",size:"sm",flat:"",round:"",icon:"fa-solid fa-plus"},{default:i(()=>[t(L,{onBeforeHide:n(k)},{default:i(()=>[t(F,{class:"my-card"},{default:i(()=>[t(M,null,{default:i(()=>[t(z,{modelValue:r.value,"onUpdate:modelValue":o[0]||(o[0]=p=>r.value=p),options:u.value,"option-value":"value","option-label":"text",label:"Alliance Ticker",outlined:"",rounded:"","use-input":"",style:{width:"200px"},onFilter:n(y)},null,8,["modelValue","options","onFilter"])]),_:1}),t(N,{align:"between"},{default:i(()=>[h(t(c,{rounded:"",color:"positive",label:"Submit",onClick:p=>n(C)(a.row.id)},null,8,["onClick"]),[[b]]),h(t(c,{rounded:"",color:"negative",label:"Close"},null,512),[[b]])]),_:2},1024)]),_:2},1024)]),_:2},1032,["onBeforeHide"])]),_:2},1024)])]),_:2},1032,["props"])]),_:1},8,["rows","columns","pagination"])]))}};export{de as default};
