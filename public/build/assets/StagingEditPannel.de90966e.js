import{u as j,i as T,r as p,c,h as b,e as h,n as C,m,C as g,f as a,w as u,q as U,aD as w,z as D,Q as N,l as r,aE as $,v as x,ah as q,G as V}from"./app.10579c01.js";import{Q as E}from"./QSelect.32be28f9.js";import"./QItemLabel.1f7b55df.js";const H={__name:"StagingEditPannel",props:{edit:{type:Boolean,default:!1},item:{type:[Object,Array],required:!1}},setup(v){const o=v;let f=j();T("can");let s=p(!1),i=p(""),S=async()=>{let t={name:i.value,system_id:n.value.value};o.edit?await axios({method:"put",withCredentials:!0,data:t,url:"/api/staging/"+o.item.id,headers:{Accept:"application/json","Content-Type":"application/json"}}).then(e=>{}):await axios({method:"post",withCredentials:!0,data:t,url:"/api/staging",headers:{Accept:"application/json","Content-Type":"application/json"}}).then(e=>{})},F=()=>{},n=p([]),d=p(),y=c(()=>d.value?f.systemlist.filter(t=>t.text.toLowerCase().indexOf(d.value)>-1):f.systemlist),k=(t,e,l)=>{e(()=>{d.value=t.toLowerCase(),y.value.length>0&&t&&(n.value=y.value[0])})},Q=()=>{o.edit&&(i.value=o.item.name,n.value=f.systemlist.find(t=>t.value==o.item.system_id))},A=c(()=>i.value==""||n.value.length==0),B=()=>{i.value="",n.value=[],d.value=""};return(t,e)=>(b(),h("div",null,[v.edit?g("",!0):(b(),C(m,{key:0,flat:"",color:"warning",icon:"fa-solid fa-plus",label:"Add Staging System",onClick:e[0]||(e[0]=l=>s.value=!s.value)})),v.edit?(b(),C(m,{key:1,flat:"",round:"",padding:"none",size:"sm",color:"secondary",icon:"fa-solid fa-edit",onClick:e[1]||(e[1]=l=>s.value=!s.value)})):g("",!0),a(q,{modelValue:s.value,"onUpdate:modelValue":e[4]||(e[4]=l=>s.value=l),persistent:"",onBeforeHide:e[5]||(e[5]=l=>r(B)()),onBeforeShow:e[6]||(e[6]=l=>r(Q)())},{default:u(()=>[a(U,{class:"myRoundTop"},{default:u(()=>[a(w,{class:"myCardHeader bg-primary text-h3 text-no-wrap"},{default:u(()=>[D(" Add new Staging ")]),_:1}),a(w,null,{default:u(()=>[a(N,{modelValue:i.value,"onUpdate:modelValue":e[2]||(e[2]=l=>i.value=l),label:"Name"},null,8,["modelValue"]),a(E,{rounded:"",class:"q-pt-sm",dense:"",standout:"","input-debounce":"0","label-color":"webway","option-value":"value","option-label":"text",modelValue:n.value,"onUpdate:modelValue":e[3]||(e[3]=l=>n.value=l),options:y.value,onFilter:r(k),onFilterAbort:r(F),label:"System","use-input":""},null,8,["modelValue","options","onFilter","onFilterAbort"])]),_:1}),a($,{align:"between"},{default:u(()=>[x(a(m,{rounded:"",color:"positive",onClick:r(S),label:o.edit?"Update":"Submit",disable:A.value},null,8,["onClick","label","disable"]),[[V]]),x(a(m,{rounded:"",color:"negative",label:"Close"},null,512),[[V]])]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{H as default};