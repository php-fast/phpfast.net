(function(){if(typeof Set!=='function'){window.Set=function(){this.items=[]};Set.prototype.add=function(item){if(this.items.indexOf(item)===-1){this.items.push(item)}
return this};Set.prototype.has=function(item){return this.items.indexOf(item)!==-1};Set.prototype.delete=function(item){var index=this.items.indexOf(item);if(index!==-1){this.items.splice(index,1);return!0}
return!1};Set.prototype.clear=function(){this.items=[]};Object.defineProperty(Set.prototype,'size',{get:function(){return this.items.length}});Set.prototype.forEach=function(callback,thisArg){for(var i=0;i<this.items.length;i++){callback.call(thisArg,this.items[i],this.items[i],this)}};console.warn('Polyfill Set has been applied. Some methods may not be fully supported yet.')}
if(window.NodeList&&!NodeList.prototype.forEach){NodeList.prototype.forEach=Array.prototype.forEach}})();(function(global){'use strict';var jFast=function(selector){if(!(this instanceof jFast)){return new jFast(selector)}
if(!selector){this.elements=[]}else if(typeof selector==='string'){this.elements=document.querySelectorAll(selector)}else if(selector instanceof NodeList||Array.isArray(selector)){this.elements=selector}else if(selector instanceof HTMLElement||selector===window||selector===document){this.elements=[selector]}else if(typeof selector==='function'){if(document.readyState==='complete'||document.readyState!=='loading'){selector()}else{document.addEventListener('DOMContentLoaded',selector)}
this.elements=[]}else{this.elements=[]}};jFast.prototype.each=function(callback){Array.prototype.forEach.call(this.elements,callback);return this};jFast.prototype.addClass=function(className){return this.each(function(element){element.classList.add(className)})};jFast.prototype.removeClass=function(className){return this.each(function(element){element.classList.remove(className)})};jFast.prototype.toggleClass=function(className){return this.each(function(element){element.classList.toggle(className)})};jFast.prototype.hasClass=function(className){return Array.prototype.some.call(this.elements,function(element){return element.classList.contains(className)})};jFast.prototype.attr=function(name,value){if(value===undefined){if(this.elements[0]){return this.elements[0].getAttribute(name)}else{return undefined}}else{return this.each(function(element){element.setAttribute(name,value)})}};jFast.prototype.text=function(value){if(value===undefined){if(this.elements[0]){return this.elements[0].textContent}else{return''}}else{return this.each(function(element){element.textContent=value})}};jFast.prototype.html=function(value){if(value===undefined){if(this.elements[0]){return this.elements[0].innerHTML}else{return''}}else{return this.each(function(element){element.innerHTML=value})}};jFast.prototype.data=function(key,value){if(value===undefined){if(this.elements[0]){return this.elements[0].dataset[key]}else{return undefined}}else{return this.each(function(element){element.dataset[key]=value})}};jFast.prototype.css=function(property,value){if(typeof property==='string'){if(value===undefined){if(this.elements[0]){return getComputedStyle(this.elements[0])[property]}else{return undefined}}else{return this.each(function(element){element.style[property]=value})}}else if(typeof property==='object'){return this.each(function(element){for(var prop in property){if(property.hasOwnProperty(prop)){element.style[prop]=property[prop]}}})}};jFast.prototype.on=function(events,selector,handler){if(typeof selector==='function'){handler=selector;selector=null}
var eventList=events.split(' ');return this.each(function(element){eventList.forEach(function(event){if(selector){element.addEventListener(event,function(e){if(e.target.matches(selector)){handler.call(e.target,e)}},!1)}else{element.addEventListener(event,handler,!1)}})})};jFast.prototype.off=function(events,handler){var eventList=events.split(' ');return this.each(function(element){eventList.forEach(function(event){element.removeEventListener(event,handler,!1)})})};jFast.prototype.trigger=function(event){var evt=new Event(event);return this.each(function(element){element.dispatchEvent(evt)})};jFast.prototype.hide=function(){return this.each(function(element){var display=getComputedStyle(element).display;if(display!=='none'){element.dataset.jFastDisplay=display}
element.style.display='none'})};jFast.prototype.show=function(){return this.each(function(element){element.style.display=element.dataset.jFastDisplay||'';delete element.dataset.jFastDisplay})};jFast.prototype.fadeIn=function(duration){duration=duration||400;return this.each(function(element){element.style.opacity=0;element.style.display=element.style.display==='none'?'':element.style.display;var last=+new Date();var tick=function(){var newOpacity=+element.style.opacity+(new Date()-last)/duration;element.style.opacity=newOpacity>1?1:newOpacity;last=+new Date();if(+element.style.opacity<1){(window.requestAnimationFrame&&requestAnimationFrame(tick))||setTimeout(tick,16)}else{element.style.opacity=''}};tick()})};jFast.prototype.fadeOut=function(duration){duration=duration||400;return this.each(function(element){element.style.opacity=1;var last=+new Date();var tick=function(){var newOpacity=+element.style.opacity-(new Date()-last)/duration;element.style.opacity=newOpacity<0?0:newOpacity;last=+new Date();if(+element.style.opacity>0){(window.requestAnimationFrame&&requestAnimationFrame(tick))||setTimeout(tick,16)}else{element.style.opacity='';element.style.display='none'}};tick()})};jFast.prototype.slideUp=function(duration){duration=duration||400;return this.each(function(element){var originalStyles={height:element.style.height,paddingTop:element.style.paddingTop,paddingBottom:element.style.paddingBottom,marginTop:element.style.marginTop,marginBottom:element.style.marginBottom,overflow:element.style.overflow,transition:element.style.transition};element.style.height=element.offsetHeight+'px';element.style.overflow='hidden';element.style.transition='height '+duration+'ms, padding '+duration+'ms, margin '+duration+'ms';setTimeout(function(){element.style.height='0';element.style.paddingTop='0';element.style.paddingBottom='0';element.style.marginTop='0';element.style.marginBottom='0'},10);setTimeout(function(){element.style.display='none';Object.assign(element.style,originalStyles)},duration)})};jFast.prototype.slideDown=function(duration){duration=duration||400;return this.each(function(element){if(getComputedStyle(element).display==='none'){element.style.display=''}
var height=element.offsetHeight;var originalStyles={height:element.style.height,paddingTop:element.style.paddingTop,paddingBottom:element.style.paddingBottom,marginTop:element.style.marginTop,marginBottom:element.style.marginBottom,overflow:element.style.overflow,transition:element.style.transition};element.style.height='0';element.style.paddingTop='0';element.style.paddingBottom='0';element.style.marginTop='0';element.style.marginBottom='0';element.style.overflow='hidden';element.style.transition='height '+duration+'ms, padding '+duration+'ms, margin '+duration+'ms';setTimeout(function(){element.style.height=height+'px';element.style.paddingTop='';element.style.paddingBottom='';element.style.marginTop='';element.style.marginBottom=''},10);setTimeout(function(){Object.assign(element.style,originalStyles)},duration)})};jFast.prototype.find=function(selector){var foundElements=new Set();this.each(function(element){var nodes=element.querySelectorAll(selector);nodes.forEach(function(node){foundElements.add(node)})});this.elements=Array.from(foundElements);return this};jFast.prototype.val=function(value){if(value===undefined){var elem=this.elements[0];if(!elem)return undefined;if(elem.tagName==='SELECT'){if(elem.multiple){var selectedOptions=Array.prototype.slice.call(elem.options).filter(function(option){return option.selected}).map(function(option){return option.value});return selectedOptions}else{return elem.value}}else if((elem.type==='checkbox'||elem.type==='radio')){return elem.checked?elem.value:undefined}else{return elem.value}}else{return this.each(function(element){if(element.tagName==='SELECT'){if(element.multiple&&Array.isArray(value)){Array.prototype.forEach.call(element.options,function(option){option.selected=value.indexOf(option.value)>-1})}else{element.value=value}}else if((element.type==='checkbox'||element.type==='radio')){if(typeof value==='boolean'){element.checked=value}else{element.value=value}}else{element.value=value}})}};jFast.prototype.prop=function(name,value){if(!name){console.warn('jFast.prop: Property name is required.');return this}
if(value===undefined){if(this.elements[0]){return this.elements[0][name]}else{return undefined}}else{return this.each(function(element){if(typeof value==='boolean'){element[name]=value}else{element.setAttribute(name,value)}})}};jFast.prototype.submit=function(){return this.each(function(element){var form;if(element.tagName==='FORM'){form=element}else if((element.tagName==='BUTTON'||element.tagName==='INPUT')&&element.type==='submit'){form=element.closest('form')}
if(form){if(typeof form.requestSubmit==='function'){form.requestSubmit(element)}else{var event=document.createEvent('Event');event.initEvent('submit',!0,!0);if(form.dispatchEvent(event)){form.submit()}}}})};jFast.prototype.parent=function(){var parents=[];this.each(function(element){if(element.parentNode){parents.push(element.parentNode)}});this.elements=parents;return this};jFast.prototype.parents=function(){var parents=[];this.each(function(element){var parent=element.parentNode;while(parent){parents.push(parent);parent=parent.parentNode}});this.elements=parents;return this};jFast.prototype.children=function(){var children=[];this.each(function(element){children=children.concat(Array.prototype.slice.call(element.children))});this.elements=children;return this};jFast.prototype.next=function(){var nextElements=[];this.each(function(element){if(element.nextElementSibling){nextElements.push(element.nextElementSibling)}});this.elements=nextElements;return this};jFast.prototype.prev=function(){var prevElements=[];this.each(function(element){if(element.previousElementSibling){prevElements.push(element.previousElementSibling)}});this.elements=prevElements;return this};jFast.prototype.siblings=function(){var siblingsSet=new Set();this.each(function(element){var parent=element.parentNode;if(!parent)return;var siblings=parent.children;for(var i=0;i<siblings.length;i++){if(siblings[i]!==element){siblingsSet.add(siblings[i])}}});this.elements=Array.from(siblingsSet);return this};jFast.prototype.append=function(content){return this.each(function(element,index){if(typeof content==='string'){element.insertAdjacentHTML('beforeend',content)}else if(content instanceof HTMLElement){element.appendChild(index===0?content:content.cloneNode(!0))}else if(content instanceof jFast||content instanceof NodeList||Array.isArray(content)){Array.prototype.forEach.call(content.elements||content,function(child){element.appendChild(index===0?child:child.cloneNode(!0))})}})};jFast.prototype.prepend=function(content){return this.each(function(element,index){if(typeof content==='string'){element.insertAdjacentHTML('afterbegin',content)}else if(content instanceof HTMLElement){element.insertBefore(index===0?content:content.cloneNode(!0),element.firstChild)}else if(content instanceof jFast||content instanceof NodeList||Array.isArray(content)){Array.prototype.forEach.call(content.elements||content,function(child){element.insertBefore(index===0?child:child.cloneNode(!0),element.firstChild)})}})};jFast.prototype.remove=function(){return this.each(function(element){if(element.parentNode){element.parentNode.removeChild(element)}})};jFast.prototype.empty=function(){return this.each(function(element){element.innerHTML=''})};jFast.each=function(collection,callback){Array.prototype.forEach.call(collection,callback)};jFast.map=function(collection,callback){return Array.prototype.map.call(collection,callback)};jFast.grep=function(array,callback){return array.filter(callback)};jFast.inArray=function(item,array){return array.indexOf(item)!==-1};jFast.prototype.filter=function(selector){var filteredElements=Array.prototype.filter.call(this.elements,function(element){return element.matches(selector)});this.elements=filteredElements;return this};jFast.ajax=function(options){var xhr=new XMLHttpRequest();xhr.open(options.type||'GET',options.url,options.async!==!1);if(options.contentType!==!1){xhr.setRequestHeader('Content-Type',options.contentType||'application/x-www-form-urlencoded; charset=UTF-8')}
if(options.dataType==='json'){xhr.setRequestHeader('Accept','application/json')}
if(options.headers){for(var header in options.headers){if(options.headers.hasOwnProperty(header)){xhr.setRequestHeader(header,options.headers[header])}}}
xhr.onload=function(){var status=xhr.status;if((status>=200&&status<300)||status===304){var response=xhr.responseText;if(options.dataType==='json'){try{response=JSON.parse(response)}catch(e){if(options.error){options.error(xhr,'parsererror',e)}
return}}
if(options.success){options.success(response,status,xhr)}}else{if(options.error){options.error(xhr,status,xhr.statusText)}}};xhr.onerror=function(){if(options.error){options.error(xhr,xhr.status,xhr.statusText)}};if(options.beforeSend){options.beforeSend(xhr)}
xhr.send(options.data||null)};global.$=jFast}(window));document.addEventListener('DOMContentLoaded',function(){const showHidePass=function(){$("#togglePassword").on("click",function(){const passwordField=$("#password");const eyeIcon=$("#eyeIcon");const type=passwordField.attr("type")==="password"?"text":"password";passwordField.attr("type",type);eyeIcon.html(type==="password"?`<path d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z" />
            <path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z" />
            <path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z" />`:`<path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />`)})};const showHidePassVerify=function(){$("#togglePasswordrepeat").on("click",function(){const passwordField=$("#password_repeat");const eyeIcon=$("#eyeIconrepeat");const type=passwordField.attr("type")==="password"?"text":"password";passwordField.attr("type",type);eyeIcon.html(type==="password"?`<path d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z" />
            <path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z" />
            <path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z" />`:`<path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />`)})};const openModal=function(){$("#openModal").on("click",function(e){})};function handleForm(nameAttribute){$(`form[name="${nameAttribute}"]`).on("input",function(e){e.preventDefault();let isFormValid=!0;$(this).find("input[required]").each(function(){if($(this).val()===""){isFormValid=!1}});$(this).find("button[type='submit']").prop("disabled",!isFormValid)})}
showHidePass();showHidePassVerify();openModal();handleForm("loginForm");handleForm("2faForm");handleForm("sigupForm");handleForm("resetPassForm");handleForm("forgotPasssForm")})