 // 格式化json
 var formatJson = function (json, options) {
     var reg = null,
         formatted = '',
         pad = 0,
         PADDING = '    ';
     options = options || {};
     options.newlineAfterColonIfBeforeBraceOrBracket = (options.newlineAfterColonIfBeforeBraceOrBracket ===
         true) ? true : false;
     options.spaceAfterColon = (options.spaceAfterColon === false) ? false : true;

     if (typeof json !== 'string') {
         json = JSON.stringify(json);
     }
     json = JSON.parse(json);
     json = JSON.stringify(json);
     reg = /([\{\}])/g;
     json = json.replace(reg, '\r\n$1\r\n');
     reg = /([\[\]])/g;
     json = json.replace(reg, '\r\n$1\r\n');
     reg = /(\,)/g;
     json = json.replace(reg, '$1\r\n');
     reg = /(\r\n\r\n)/g;
     json = json.replace(reg, '\r\n');
     reg = /\r\n\,/g;
     json = json.replace(reg, ',');
     if (!options.newlineAfterColonIfBeforeBraceOrBracket) {
         reg = /\:\r\n\{/g;
         json = json.replace(reg, ':{');
         reg = /\:\r\n\[/g;
         json = json.replace(reg, ':[');
     }
     if (options.spaceAfterColon) {
         reg = /\:/g;
         json = json.replace(reg, ': ');
     }
     json.split('\r\n').forEach(function (node, index) {
         var i = 0,
             indent = 0,
             padding = '';

         if (node.match(/\{$/) || node.match(/\[$/)) {
             indent = 1;
         } else if (node.match(/\}/) || node.match(/\]/)) {
             if (pad !== 0) {
                 pad -= 1;
             }
         } else {
             indent = 0;
         }

         for (i = 0; i < pad; i++) {
             padding += PADDING;
         }
         formatted += padding + node + '\r\n';
         pad += indent;
     });
     return formatted;
 };
 /**
  * 复制内容到粘贴板
  * content : 需要复制的内容
  * message : 复制完后的提示，不传则默认提示"复制成功"
  */
 function copyToClip(content) {
     var aux = document.createElement("input");
     aux.setAttribute("value", content);
     document.body.appendChild(aux);
     aux.select();
     document.execCommand("copy");
     document.body.removeChild(aux);
 }

 //跳转动画
 function scollAnimate(el, time = 10) {
     let to = document.querySelector(el).offsetTop;
     let curr_top = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
     let time_id = setInterval(() => {
         curr_top += 10;
         document.body.scollTop = curr_top;
         document.documentElement.scrollTop = curr_top;
         if (curr_top >= to) {
             clearInterval(time_id);
         }
     }, time);
 }

 //get
 function get(url) {
     return new Promise(function (resolve, reject) {
         var xml = new XMLHttpRequest();
         xml.open("GET", url, true);
         xml.send();
         xml.onreadystatechange = function () {
             if (xml.readyState == 4 && xml.status == 200) {
                 resolve(xml.responseText)
             }
         }
     })
 }

 function test() {
     var url = document.querySelector("#url").value;
     var result = document.querySelector("#result");
     get(url).then(function (res) {
         let json = formatJson(res);
         result.innerHTML = json;
     })

 }