import{J as ee,aZ as te,a_ as ae,a$ as oe,b0 as w,b1 as ne,b2 as ie,r as k,c as f,b3 as C,b4 as le,b5 as se,b6 as re,b7 as ue,b8 as ce,b9 as de,ba as fe,$ as E,b as x,bb as H,aO as A,bc as ve,aN as q,aK as D,ao as he,W as M,aP as ge,V as me,bd as be,ak as ye,Y as Te}from"./app.10579c01.js";const Pe=ee({name:"QTooltip",inheritAttrs:!1,props:{...te,...ae,...oe,maxHeight:{type:String,default:null},maxWidth:{type:String,default:null},transitionShow:{default:"jump-down"},transitionHide:{default:"jump-up"},anchor:{type:String,default:"bottom middle",validator:w},self:{type:String,default:"top middle",validator:w},offset:{type:Array,default:()=>[14,14],validator:ne},scrollTarget:{default:void 0},delay:{type:Number,default:0},hideDelay:{type:Number,default:0}},emits:[...ie],setup(e,{slots:j,emit:b,attrs:v}){let i,l;const h=me(),{proxy:{$q:o}}=h,s=k(null),c=k(!1),L=f(()=>C(e.anchor,o.lang.rtl)),W=f(()=>C(e.self,o.lang.rtl)),N=f(()=>e.persistent!==!0),{registerTick:Q,removeTick:_}=le(),{registerTimeout:d}=se(),{transitionProps:$,transitionStyle:R}=re(e),{localScrollTarget:y,changeScrollEvent:V,unconfigureScrollTarget:B}=ue(e,S),{anchorEl:a,canShow:I,anchorEvents:r}=ce({showing:c,configureAnchorEl:F}),{show:J,hide:g}=de({showing:c,canShow:I,handleShow:U,handleHide:Y,hideOnRouteChange:N,processOnMount:!0});Object.assign(r,{delayShow:Z,delayHide:z});const{showPortal:T,hidePortal:p,renderPortal:K}=fe(h,s,X,"tooltip");if(o.platform.is.mobile===!0){const t={anchorEl:a,innerRef:s,onClickOutside(n){return g(n),n.target.classList.contains("q-dialog__backdrop")&&Te(n),!0}},m=f(()=>e.modelValue===null&&e.persistent!==!0&&c.value===!0);E(m,n=>{(n===!0?be:H)(t)}),x(()=>{H(t)})}function U(t){T(),Q(()=>{l=new MutationObserver(()=>u()),l.observe(s.value,{attributes:!1,childList:!0,characterData:!0,subtree:!0}),u(),S()}),i===void 0&&(i=E(()=>o.screen.width+"|"+o.screen.height+"|"+e.self+"|"+e.anchor+"|"+o.lang.rtl,u)),d(()=>{T(!0),b("show",t)},e.transitionDuration)}function Y(t){_(),p(),P(),d(()=>{p(!0),b("hide",t)},e.transitionDuration)}function P(){l!==void 0&&(l.disconnect(),l=void 0),i!==void 0&&(i(),i=void 0),B(),A(r,"tooltipTemp")}function u(){const t=s.value;a.value===null||!t||ve({el:t,offset:e.offset,anchorEl:a.value,anchorOrigin:L.value,selfOrigin:W.value,maxHeight:e.maxHeight,maxWidth:e.maxWidth})}function Z(t){if(o.platform.is.mobile===!0){q(),document.body.classList.add("non-selectable");const m=a.value,n=["touchmove","touchcancel","touchend","click"].map(O=>[m,O,"delayHide","passiveCapture"]);D(r,"tooltipTemp",n)}d(()=>{J(t)},e.delay)}function z(t){o.platform.is.mobile===!0&&(A(r,"tooltipTemp"),q(),setTimeout(()=>{document.body.classList.remove("non-selectable")},10)),d(()=>{g(t)},e.hideDelay)}function F(){if(e.noParentEvent===!0||a.value===null)return;const t=o.platform.is.mobile===!0?[[a.value,"touchstart","delayShow","passive"]]:[[a.value,"mouseenter","delayShow","passive"],[a.value,"mouseleave","delayHide","passive"]];D(r,"anchor",t)}function S(){if(a.value!==null||e.scrollTarget!==void 0){y.value=he(a.value,e.scrollTarget);const t=e.noParentEvent===!0?u:g;V(y.value,t)}}function G(){return c.value===!0?M("div",{...v,ref:s,class:["q-tooltip q-tooltip--style q-position-engine no-pointer-events",v.class],style:[v.style,R.value],role:"tooltip"},ye(j.default)):null}function X(){return M(ge,$.value,G)}return x(P),Object.assign(h.proxy,{updatePosition:u}),K}});export{Pe as Q};