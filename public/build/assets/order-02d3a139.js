import{_ as L,r as S,b as g,w as e,V as J,o as s,d as l,e as f,f as v,g as x,h as O,i as h,j as C,c as n,k as b,F as m,l as U,a as r,t as _,m as q,n as z,p as D,q as E,s as K,u as M}from"./main-72e991bb.js";const j=k=>(K("data-v-5b1c78c2"),k=k(),M(),k),R=j(()=>r("h1",null,"طلب مشتريات",-1)),A={class:"d-f-row"},G={style:{display:"inline-block","font-size":"25px","font-weight":"bold",padding:"5px 10px",border:"blue solid 1px","border-radius":"7px"}},H={class:"d-f-row"},P={class:"box-item"},Q=j(()=>r("br",null,null,-1)),W={key:0},X={__name:"order",setup(k){const d=S(JSON.parse(localStorage.getItem("mainItems"))||[]),V=S(""),p=S("");function I(u){if(u.trim()!==""){const a={[u]:[]};d.value.push(a),V.value="",y()}}function $(u,a){p.value.trim()!==""&&(d.value[u][a].push(Number(p.value)),p.value="",y())}function N(u,a,t){d.value[u][a].splice(t,1),y()}function B(u){d.value.splice(u,1),y()}function T(u,a){return d.value[u][a].reduce((t,o)=>t+o,0)}function y(){localStorage.setItem("mainItems",JSON.stringify(d.value))}return(u,a)=>(s(),g(J,null,{default:e(()=>[l(v,null,{default:e(()=>[l(f,{cols:"12"},{default:e(()=>[R]),_:1})]),_:1}),l(v,null,{default:e(()=>[l(f,{cols:"12"},{default:e(()=>[l(x,{class:"pa-3 d-f-row"},{default:e(()=>[l(O,{type:"text",variant:"outlined",label:"اسم البند",modelValue:V.value,"onUpdate:modelValue":a[0]||(a[0]=t=>V.value=t)},null,8,["modelValue"]),l(h,{class:"ma-3",onClick:a[1]||(a[1]=t=>I(V.value)),color:"success"},{default:e(()=>[C(" إضافة بند ")]),_:1})]),_:1})]),_:1})]),_:1}),(s(!0),n(m,null,b(d.value,(t,o)=>(s(),g(v,{key:o},{default:e(()=>[l(f,{cols:"12"},{default:e(()=>[l(x,{class:"ma-3"},{default:e(()=>[l(U,null,{default:e(()=>[r("h3",null,_(Object.keys(t)[0]),1)]),_:2},1024),l(q,null,{default:e(()=>[l(v,null,{default:e(()=>[l(f,{cols:"8"},{default:e(()=>[l(O,{type:"text",variant:"outlined",label:` إضافة ${Object.keys(t)[0]}`,modelValue:p.value,"onUpdate:modelValue":a[2]||(a[2]=c=>p.value=c)},null,8,["label","modelValue"])]),_:2},1024),l(f,{cols:"4"},{default:e(()=>[l(h,{onClick:c=>$(o,Object.keys(t)[0]),color:"success"},{default:e(()=>[C(_(`إضافة ${Object.keys(t)[0]}`),1)]),_:2},1032,["onClick"])]),_:2},1024)]),_:2},1024),l(z,null,{default:e(()=>[(s(!0),n(m,null,b(t[Object.keys(t)[0]],(c,i)=>(s(),n("ul",{key:i},[r("li",A,[r("span",G,_(c),1),l(h,{color:"red",onClick:w=>N(o,Object.keys(t)[0],i)},{default:e(()=>[l(D,null,{default:e(()=>[C("mdi-delete")]),_:1})]),_:2},1032,["onClick"])])]))),128))]),_:2},1024),l(h,{onClick:c=>B(o),color:"red"},{default:e(()=>[C(_(`حذف ${Object.keys(t)[0]}`),1)]),_:2},1032,["onClick"])]),_:2},1024)]),_:2},1024)]),_:2},1024)]),_:2},1024))),128)),l(x,{class:"ma-3 pa-2",id:"textShare"},{default:e(()=>[(s(!0),n(m,null,b(d.value,(t,o)=>(s(),g(v,{key:o},{default:e(()=>[l(f,{cols:"12"},{default:e(()=>[(s(!0),n(m,null,b(t,(c,i)=>(s(),n("div",{key:i},[r("h2",null,_(i),1),(s(!0),n(m,null,b(c,(w,F)=>(s(),n("ul",{key:F},[r("li",H,[r("span",P,_(w),1)])]))),128)),Q,c.length>0?(s(),n("h4",W,"إجمالي الفلوس "+_(T(o,i)),1)):E("",!0)]))),128))]),_:2},1024)]),_:2},1024))),128))]),_:1})]),_:1}))}},Z=L(X,[["__scopeId","data-v-5b1c78c2"]]);export{Z as default};
