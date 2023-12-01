import{T as j,d as f,o as d,c as w,w as a,g as r,e as S,a as l,b as o,l as p,z as v,G as B,i as h,f as _,u as t,j as E,n as z,O as A}from"./app-a846be57.js";import{_ as F}from"./ActionMessage-473f4e1b.js";import{_ as O}from"./FormSection-61aadcf5.js";import{a as g,_ as V}from"./TextInput-faf7bb33.js";import{_ as k}from"./InputLabel-8d9c7e6b.js";import{_ as R}from"./PrimaryButton-06f52da3.js";import{_ as C}from"./SecondaryButton-c79b44b9.js";import"./SectionTitle-8d90c1db.js";import"./_plugin-vue_export-helper-c27b6911.js";const T={key:0,class:"col-span-6 sm:col-span-4"},q={class:"mt-2"},D=["src","alt"],L={class:"mt-2"},G={class:"col-span-6 sm:col-span-4"},M={class:"col-span-6 sm:col-span-4"},Y={key:0},H={class:"text-sm mt-2"},J={class:"mt-2 font-medium text-sm text-green-600"},ae={__name:"UpdateProfileInformationForm",props:{user:Object},setup(u){const y=u,e=j({_method:"PUT",name:y.user.name,email:y.user.email,photo:null}),b=f(null),c=f(null),n=f(null),$=()=>{n.value&&(e.photo=n.value.files[0]),e.post(route("user-profile-information.update"),{errorBag:"updateProfileInformation",preserveScroll:!0,onSuccess:()=>P()})},x=()=>{b.value=!0},I=()=>{n.value.click()},N=()=>{const s=n.value.files[0];if(!s)return;const i=new FileReader;i.onload=m=>{c.value=m.target.result},i.readAsDataURL(s)},U=()=>{A.delete(route("current-user-photo.destroy"),{preserveScroll:!0,onSuccess:()=>{c.value=null,P()}})},P=()=>{var s;(s=n.value)!=null&&s.value&&(n.value.value=null)};return(s,i)=>(d(),w(O,{onSubmitted:$},{title:a(()=>[r(" Profile Information ")]),description:a(()=>[r(" Update your account's profile information and email address. ")]),form:a(()=>[s.$page.props.jetstream.managesProfilePhotos?(d(),S("div",T,[l("input",{id:"photo",ref_key:"photoInput",ref:n,type:"file",class:"hidden",onChange:N},null,544),o(k,{for:"photo",value:"Photo"}),p(l("div",q,[l("img",{src:u.user.profile_photo_url,alt:u.user.name,class:"rounded-full h-20 w-20 object-cover"},null,8,D)],512),[[v,!c.value]]),p(l("div",L,[l("span",{class:"block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center",style:B("background-image: url('"+c.value+"');")},null,4)],512),[[v,c.value]]),o(C,{class:"mt-2 me-2",type:"button",onClick:h(I,["prevent"])},{default:a(()=>[r(" Select A New Photo ")]),_:1},8,["onClick"]),u.user.profile_photo_path?(d(),w(C,{key:0,type:"button",class:"mt-2",onClick:h(U,["prevent"])},{default:a(()=>[r(" Remove Photo ")]),_:1},8,["onClick"])):_("",!0),o(g,{message:t(e).errors.photo,class:"mt-2"},null,8,["message"])])):_("",!0),l("div",G,[o(k,{for:"name",value:"Name"}),o(V,{id:"name",modelValue:t(e).name,"onUpdate:modelValue":i[0]||(i[0]=m=>t(e).name=m),type:"text",class:"mt-1 block w-full",required:"",autocomplete:"name"},null,8,["modelValue"]),o(g,{message:t(e).errors.name,class:"mt-2"},null,8,["message"])]),l("div",M,[o(k,{for:"email",value:"Email"}),o(V,{id:"email",modelValue:t(e).email,"onUpdate:modelValue":i[1]||(i[1]=m=>t(e).email=m),type:"email",class:"mt-1 block w-full",required:"",autocomplete:"username"},null,8,["modelValue"]),o(g,{message:t(e).errors.email,class:"mt-2"},null,8,["message"]),s.$page.props.jetstream.hasEmailVerification&&u.user.email_verified_at===null?(d(),S("div",Y,[l("p",H,[r(" Your email address is unverified. "),o(t(E),{href:s.route("verification.send"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",onClick:h(x,["prevent"])},{default:a(()=>[r(" Click here to re-send the verification email. ")]),_:1},8,["href","onClick"])]),p(l("div",J," A new verification link has been sent to your email address. ",512),[[v,b.value]])])):_("",!0)])]),actions:a(()=>[o(F,{on:t(e).recentlySuccessful,class:"me-3"},{default:a(()=>[r(" Saved. ")]),_:1},8,["on"]),o(R,{class:z({"opacity-25":t(e).processing}),disabled:t(e).processing},{default:a(()=>[r(" Save ")]),_:1},8,["class","disabled"])]),_:1}))}};export{ae as default};
