import{r as m,h as f,e as C,f as e,m as o,w as t,q as x,aD as g,k as s,aE as w,v as i,l as v,ah as h,j as r,z as _,aX as k,G as c}from"./app.10579c01.js";const Q=r("span",{class:"q-ml-sm text-h4"},[_("Are you sure "),r("span",{class:"text-pink-13 text-bold"},"EMILY!!!!!!!!!!")],-1),b={__name:"NewCustomCampaignDeleteButton",props:{item:Object},setup(d){const u=d;let l=m(!1),p=async()=>{await k({method:"delete",url:"/api/newoperation/"+u.item.id,withCredentials:!0,headers:{Accept:"application/json","Content-Type":"application/json"}})};return(V,a)=>(f(),C("div",null,[e(o,{color:"negative",icon:"fa-solid fa-trash-can",flat:"",size:"sm",round:"",onClick:a[0]||(a[0]=n=>l.value=!0)}),e(h,{modelValue:l.value,"onUpdate:modelValue":a[1]||(a[1]=n=>l.value=n),persistent:""},{default:t(()=>[e(x,null,{default:t(()=>[e(g,{class:"row items-center"},{default:t(()=>[e(s,{name:"fa-solid fa-triangle-exclamation",color:"warning",size:"xl"}),Q,e(s,{name:"fa-solid fa-triangle-exclamation",color:"warning",size:"xl"})]),_:1}),e(w,{align:"right"},{default:t(()=>[i(e(o,{rounded:"",label:"Cancel",color:"negative"},null,512),[[c]]),i(e(o,{rounded:"",label:"Yes",color:"primary",onClick:v(p)},null,8,["onClick"]),[[c]])]),_:1})]),_:1})]),_:1},8,["modelValue"])]))}};export{b as default};
