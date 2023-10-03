import{bh as Y,J as z,bi as Q,a_ as F,O as X,b2 as Z,S as p,r as q,bj as L,b9 as ee,c as o,$ as m,b as te,W as n,V as ne,x as ae,v as ie,aW as oe,ak as le,y as x,k as w,Y as ue}from"./app.10579c01.js";import{Q as y}from"./QItemLabel.1f7b55df.js";import{Q as ce}from"./QSlideTransition.6d4d03a3.js";import{Q as C}from"./QSeparator.0566634d.js";const u=Y({}),de=Object.keys(Q),fe=z({name:"QExpansionItem",props:{...Q,...F,...X,icon:String,label:String,labelLines:[Number,String],caption:String,captionLines:[Number,String],dense:Boolean,toggleAriaLabel:String,expandIcon:String,expandedIcon:String,expandIconClass:[Array,String,Object],duration:Number,headerInsetLevel:Number,contentInsetLevel:Number,expandSeparator:Boolean,defaultOpened:Boolean,hideExpandIcon:Boolean,expandIconToggle:Boolean,switchToggleSide:Boolean,denseToggle:Boolean,group:String,popup:Boolean,headerStyle:[Array,String,Object],headerClass:[Array,String,Object]},emits:[...Z,"click","afterShow","afterHide"],setup(e,{slots:g,emit:v}){const{proxy:{$q:d}}=ne(),f=p(e,d),a=q(e.modelValue!==null?e.modelValue:e.defaultOpened),b=q(null),S=L(),{show:A,hide:I,toggle:h}=ee({showing:a});let l,c;const O=o(()=>`q-expansion-item q-item-type q-expansion-item--${a.value===!0?"expanded":"collapsed"} q-expansion-item--${e.popup===!0?"popup":"standard"}`),B=o(()=>e.contentInsetLevel===void 0?null:{["padding"+(d.lang.rtl===!0?"Right":"Left")]:e.contentInsetLevel*56+"px"}),r=o(()=>e.disable!==!0&&(e.href!==void 0||e.to!==void 0&&e.to!==null&&e.to!=="")),j=o(()=>{const t={};return de.forEach(i=>{t[i]=e[i]}),t}),P=o(()=>r.value===!0||e.expandIconToggle!==!0),E=o(()=>e.expandedIcon!==void 0&&a.value===!0?e.expandedIcon:e.expandIcon||d.iconSet.expansionItem[e.denseToggle===!0?"denseIcon":"icon"]),H=o(()=>e.disable!==!0&&(r.value===!0||e.expandIconToggle===!0)),N=o(()=>({expanded:a.value===!0,detailsId:e.targetUid,toggle:h,show:A,hide:I})),T=o(()=>{const t=e.toggleAriaLabel!==void 0?e.toggleAriaLabel:d.lang.label[a.value===!0?"collapse":"expand"](e.label);return{role:"button","aria-expanded":a.value===!0?"true":"false","aria-controls":S,"aria-label":t}});m(()=>e.group,t=>{c!==void 0&&c(),t!==void 0&&_()});function R(t){r.value!==!0&&h(t),v("click",t)}function $(t){t.keyCode===13&&k(t,!0)}function k(t,i){i!==!0&&b.value!==null&&b.value.focus(),h(t),ue(t)}function D(){v("afterShow")}function G(){v("afterHide")}function _(){l===void 0&&(l=L()),a.value===!0&&(u[e.group]=l);const t=m(a,s=>{s===!0?u[e.group]=l:u[e.group]===l&&delete u[e.group]}),i=m(()=>u[e.group],(s,J)=>{J===l&&s!==void 0&&s!==l&&I()});c=()=>{t(),i(),u[e.group]===l&&delete u[e.group],c=void 0}}function K(){const t={class:[`q-focusable relative-position cursor-pointer${e.denseToggle===!0&&e.switchToggleSide===!0?" items-end":""}`,e.expandIconClass],side:e.switchToggleSide!==!0,avatar:e.switchToggleSide},i=[n(w,{class:"q-expansion-item__toggle-icon"+(e.expandedIcon===void 0&&a.value===!0?" q-expansion-item__toggle-icon--rotated":""),name:E.value})];return H.value===!0&&(Object.assign(t,{tabindex:0,...T.value,onClick:k,onKeyup:$}),i.unshift(n("div",{ref:b,class:"q-expansion-item__toggle-focus q-icon q-focus-helper q-focus-helper--rounded",tabindex:-1}))),n(x,t,()=>i)}function M(){let t;return g.header!==void 0?t=[].concat(g.header(N.value)):(t=[n(x,()=>[n(y,{lines:e.labelLines},()=>e.label||""),e.caption?n(y,{lines:e.captionLines,caption:!0},()=>e.caption):null])],e.icon&&t[e.switchToggleSide===!0?"push":"unshift"](n(x,{side:e.switchToggleSide===!0,avatar:e.switchToggleSide!==!0},()=>n(w,{name:e.icon})))),e.disable!==!0&&e.hideExpandIcon!==!0&&t[e.switchToggleSide===!0?"unshift":"push"](K()),t}function U(){const t={ref:"item",style:e.headerStyle,class:e.headerClass,dark:f.value,disable:e.disable,dense:e.dense,insetLevel:e.headerInsetLevel};return P.value===!0&&(t.clickable=!0,t.onClick=R,Object.assign(t,r.value===!0?j.value:T.value)),n(ae,t,M)}function V(){return ie(n("div",{key:"e-content",class:"q-expansion-item__content relative-position",style:B.value,id:S},le(g.default)),[[oe,a.value]])}function W(){const t=[U(),n(ce,{duration:e.duration,onShow:D,onHide:G},V)];return e.expandSeparator===!0&&t.push(n(C,{class:"q-expansion-item__border q-expansion-item__border--top absolute-top",dark:f.value}),n(C,{class:"q-expansion-item__border q-expansion-item__border--bottom absolute-bottom",dark:f.value})),t}return e.group!==void 0&&_(),te(()=>{c!==void 0&&c()}),()=>n("div",{class:O.value},[n("div",{class:"q-expansion-item__container relative-position"},W())])}});export{fe as Q};
