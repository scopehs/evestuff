import{Q as i}from"./QCircularProgress.68264667.js";import{c as l,h as _,e as h,j as n,f as d,w as c,z as m,A as o}from"./app.10579c01.js";const p={class:"row"},f={class:"col"},x=n("span",null," Nodes -",-1),g={__name:"SystemNodeCount",props:{item:Object},setup(v){const s=v;let a=l(()=>{var e=null;return s.item?e=s.item.length:e=0,e}),r=l(()=>{var e=null;return s.item?e=s.item.filter(t=>t.node_status.id==2||t.node_status.id==3||t.node_status.id==4||t.node_status.id==8).length:e=0,e}),u=l(()=>{var e=null;return s.item?e=s.item.filter(t=>t.node_status.id==5||t.node_status.id==7).length:e=0,e});return(e,t)=>(_(),h("div",null,[n("div",p,[n("div",f,[x,d(i,{"show-value":"",max:a.value,min:0,rounded:"",value:r.value,size:"45px",thickness:.1,color:"positive","track-color":"green-3",class:""},{default:c(()=>[m(o(r.value)+"/"+o(a.value),1)]),_:1},8,["max","value","thickness"]),d(i,{"show-value":"",max:a.value,min:0,rounded:"",value:u.value,size:"45px",thickness:.1,color:"negative","track-color":"red-3",class:""},{default:c(()=>[m(o(u.value)+"/"+o(a.value),1)]),_:1},8,["max","value","thickness"])])])]))}};export{g as default};