import{a as p}from"./QSelect.32be28f9.js";import{u as i,c as n,h as e,e as t,F as d,t as _,f,C as y}from"./app.10579c01.js";import"./QItemLabel.1f7b55df.js";const g={key:0},B={__name:"StartSystemItemList",props:{campaignID:Number},setup(o){const a=o;let r=i(),l=n(()=>r.getStartJoinById(a.campaignID)!=0),u=n(()=>r.getStartJoinById(a.campaignID));return(s,I)=>(e(),t("div",null,[l.value?(e(),t("span",g,[(e(!0),t(d,null,_(u.value,(c,m)=>(e(),t("span",{key:m,class:"pr-2"},[f(p,{dense:"",label:c.constellation_name,color:"red"},null,8,["label"])]))),128))])):y("",!0)]))}};export{B as default};
