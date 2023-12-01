import{d as p,C as N,o as t,e as s,a as l,r as Q,b as a,w as o,g as n,t as k,B as R,n as g,m as D,Q as O,T as Y,k as I,D as U,c as h,f as u,u as $,F as z,h as G,O as B}from"./app-a846be57.js";import{b as W,a as j}from"./DialogModal-a52ad410.js";import{_ as L,a as M}from"./TextInput-faf7bb33.js";import{_ as V}from"./PrimaryButton-06f52da3.js";import{_ as C}from"./SecondaryButton-c79b44b9.js";import{_ as J}from"./DangerButton-14981f57.js";import{_ as X}from"./InputLabel-8d9c7e6b.js";import"./SectionTitle-8d90c1db.js";import"./_plugin-vue_export-helper-c27b6911.js";const Z={class:"mt-4"},y={__name:"ConfirmsPassword",props:{title:{type:String,default:"Confirm Password"},content:{type:String,default:"For your security, please confirm your password to continue."},button:{type:String,default:"Confirm"}},emits:["confirmed"],setup(w,{emit:T}){const b=T,c=p(!1),e=N({password:"",error:"",processing:!1}),i=p(null),_=()=>{axios.get(route("password.confirmation")).then(r=>{r.data.confirmed?b("confirmed"):(c.value=!0,setTimeout(()=>i.value.focus(),250))})},v=()=>{e.processing=!0,axios.post(route("password.confirm"),{password:e.password}).then(()=>{e.processing=!1,d(),D().then(()=>b("confirmed"))}).catch(r=>{e.processing=!1,e.error=r.response.data.errors.password[0],i.value.focus()})},d=()=>{c.value=!1,e.password="",e.error=""};return(r,m)=>(t(),s("span",null,[l("span",{onClick:_},[Q(r.$slots,"default")]),a(W,{show:c.value,onClose:d},{title:o(()=>[n(k(w.title),1)]),content:o(()=>[n(k(w.content)+" ",1),l("div",Z,[a(L,{ref_key:"passwordInput",ref:i,modelValue:e.password,"onUpdate:modelValue":m[0]||(m[0]=S=>e.password=S),type:"password",class:"mt-1 block w-3/4",placeholder:"Password",autocomplete:"current-password",onKeyup:R(v,["enter"])},null,8,["modelValue","onKeyup"]),a(M,{message:e.error,class:"mt-2"},null,8,["message"])])]),footer:o(()=>[a(C,{onClick:d},{default:o(()=>[n(" Cancel ")]),_:1}),a(V,{class:g(["ms-3",{"opacity-25":e.processing}]),disabled:e.processing,onClick:v},{default:o(()=>[n(k(w.button),1)]),_:1},8,["class","disabled"])]),_:1},8,["show"])]))}},ee={key:0,class:"text-lg font-medium text-gray-900"},te={key:1,class:"text-lg font-medium text-gray-900"},oe={key:2,class:"text-lg font-medium text-gray-900"},se=l("div",{class:"mt-3 max-w-xl text-sm text-gray-600"},[l("p",null," When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application. ")],-1),ae={key:3},ne={key:0},re={class:"mt-4 max-w-xl text-sm text-gray-600"},le={key:0,class:"font-semibold"},ce={key:1},ie=["innerHTML"],ue={key:0,class:"mt-4 max-w-xl text-sm text-gray-600"},de={class:"font-semibold"},me=["innerHTML"],fe={key:1,class:"mt-4"},pe={key:1},ve=l("div",{class:"mt-4 max-w-xl text-sm text-gray-600"},[l("p",{class:"font-semibold"}," Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost. ")],-1),_e={class:"grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg"},he={class:"mt-5"},ye={key:0},we={key:1},Ve={__name:"TwoFactorAuthenticationForm",props:{requiresConfirmation:Boolean},setup(w){const T=w,b=O(),c=p(!1),e=p(!1),i=p(!1),_=p(null),v=p(null),d=p([]),r=Y({code:""}),m=I(()=>{var f;return!c.value&&((f=b.props.auth.user)==null?void 0:f.two_factor_enabled)});U(m,()=>{m.value||(r.reset(),r.clearErrors())});const S=()=>{c.value=!0,B.post(route("two-factor.enable"),{},{preserveScroll:!0,onSuccess:()=>Promise.all([q(),E(),F()]),onFinish:()=>{c.value=!1,e.value=T.requiresConfirmation}})},q=()=>axios.get(route("two-factor.qr-code")).then(f=>{_.value=f.data.svg}),E=()=>axios.get(route("two-factor.secret-key")).then(f=>{v.value=f.data.secretKey}),F=()=>axios.get(route("two-factor.recovery-codes")).then(f=>{d.value=f.data}),K=()=>{r.post(route("two-factor.confirm"),{errorBag:"confirmTwoFactorAuthentication",preserveScroll:!0,preserveState:!0,onSuccess:()=>{e.value=!1,_.value=null,v.value=null}})},H=()=>{axios.post(route("two-factor.recovery-codes")).then(()=>F())},A=()=>{i.value=!0,B.delete(route("two-factor.disable"),{preserveScroll:!0,onSuccess:()=>{i.value=!1,e.value=!1}})};return(f,P)=>(t(),h(j,null,{title:o(()=>[n(" Two Factor Authentication ")]),description:o(()=>[n(" Add additional security to your account using two factor authentication. ")]),content:o(()=>[m.value&&!e.value?(t(),s("h3",ee," You have enabled two factor authentication. ")):m.value&&e.value?(t(),s("h3",te," Finish enabling two factor authentication. ")):(t(),s("h3",oe," You have not enabled two factor authentication. ")),se,m.value?(t(),s("div",ae,[_.value?(t(),s("div",ne,[l("div",re,[e.value?(t(),s("p",le," To finish enabling two factor authentication, scan the following QR code using your phone's authenticator application or enter the setup key and provide the generated OTP code. ")):(t(),s("p",ce," Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application or enter the setup key. "))]),l("div",{class:"mt-4 p-2 inline-block bg-white",innerHTML:_.value},null,8,ie),v.value?(t(),s("div",ue,[l("p",de,[n(" Setup Key: "),l("span",{innerHTML:v.value},null,8,me)])])):u("",!0),e.value?(t(),s("div",fe,[a(X,{for:"code",value:"Code"}),a(L,{id:"code",modelValue:$(r).code,"onUpdate:modelValue":P[0]||(P[0]=x=>$(r).code=x),type:"text",name:"code",class:"block mt-1 w-1/2",inputmode:"numeric",autofocus:"",autocomplete:"one-time-code",onKeyup:R(K,["enter"])},null,8,["modelValue","onKeyup"]),a(M,{message:$(r).errors.code,class:"mt-2"},null,8,["message"])])):u("",!0)])):u("",!0),d.value.length>0&&!e.value?(t(),s("div",pe,[ve,l("div",_e,[(t(!0),s(z,null,G(d.value,x=>(t(),s("div",{key:x},k(x),1))),128))])])):u("",!0)])):u("",!0),l("div",he,[m.value?(t(),s("div",we,[a(y,{onConfirmed:K},{default:o(()=>[e.value?(t(),h(V,{key:0,type:"button",class:g(["me-3",{"opacity-25":c.value}]),disabled:c.value},{default:o(()=>[n(" Confirm ")]),_:1},8,["class","disabled"])):u("",!0)]),_:1}),a(y,{onConfirmed:H},{default:o(()=>[d.value.length>0&&!e.value?(t(),h(C,{key:0,class:"me-3"},{default:o(()=>[n(" Regenerate Recovery Codes ")]),_:1})):u("",!0)]),_:1}),a(y,{onConfirmed:F},{default:o(()=>[d.value.length===0&&!e.value?(t(),h(C,{key:0,class:"me-3"},{default:o(()=>[n(" Show Recovery Codes ")]),_:1})):u("",!0)]),_:1}),a(y,{onConfirmed:A},{default:o(()=>[e.value?(t(),h(C,{key:0,class:g({"opacity-25":i.value}),disabled:i.value},{default:o(()=>[n(" Cancel ")]),_:1},8,["class","disabled"])):u("",!0)]),_:1}),a(y,{onConfirmed:A},{default:o(()=>[e.value?u("",!0):(t(),h(J,{key:0,class:g({"opacity-25":i.value}),disabled:i.value},{default:o(()=>[n(" Disable ")]),_:1},8,["class","disabled"]))]),_:1})])):(t(),s("div",ye,[a(y,{onConfirmed:S},{default:o(()=>[a(V,{type:"button",class:g({"opacity-25":c.value}),disabled:c.value},{default:o(()=>[n(" Enable ")]),_:1},8,["class","disabled"])]),_:1})]))])]),_:1}))}};export{Ve as default};
