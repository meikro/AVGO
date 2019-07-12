function ZoomPic() {
    this.initialize.apply(this, arguments)
}
ZoomPic.prototype = {
    initialize: function (id) {
        var _this = this;
        this.wrap = typeof id === "string" ? document.getElementById(id) : id;
        this.oUl = this.wrap.getElementsByTagName("ul")[0];
        this.aLi = this.wrap.getElementsByTagName("li");
        this.prev = document.getElementById("pre");
        this.next = document.getElementById("next");
        this.timer = null;
        this.aSort = [];
        this.iCenter = 3;
        this._doPrev = function () {
            return _this.doPrev.apply(_this)
        };
        this._doNext = function () {
            return _this.doNext.apply(_this)
        };
        this.options = [{
            width: 399,
            height: 240,
            top: 30,
            left: 0,
            zIndex: 1
        }, {
            width: 444,
            height: 267,
            top: 16,
            left: 160,
            zIndex: 2
        }, {
            width: 500,
            height: 300,
            top: 0,
            left: 366,
            zIndex: 3
        }, {
            width: 444,
            height: 267,
            top: 16,
            left: 629,
            zIndex: 2
        }, {
            width: 399,
            height: 240,
            top: 30,
            left: 834,
            zIndex: 1
        },];
        for (var i = 0; i < this.aLi.length; i++) this.aSort[i] = this.aLi[i];
        this.setUp();
        this.addEvent(this.prev, "click", this._doPrev);
        this.addEvent(this.next, "click", this._doNext);
    },
    doPrev: function () {
        this.aSort.unshift(this.aSort.pop());
        this.setUp()
    },
    doNext: function () {
        this.aSort.push(this.aSort.shift());
        this.setUp()
    },
    setUp: function () {
        var _this = this;
        var i = 0;
        for (i = 0; i < this.aSort.length; i++) this.oUl.appendChild(this.aSort[i]);
       
        for (i = 0; i < this.aSort.length; i++) {
            this.aSort[i].index = i;
            if (i < this.aSort.length) {
                this.css(this.aSort[i], "display", "block");
                this.doMove(this.aSort[i], this.options[i], function () {
                    _this.doMove(_this.aSort[_this.iCenter].getElementsByTagName("img")[0], {
                        opacity: 100
                    })
                });
            } else {
                this.css(this.aSort[i], "display", "none");
                this.css(this.aSort[i], "width", 0);
                this.css(this.aSort[i], "height", 0);
                this.css(this.aSort[i], "top", 30);
                this.css(this.aSort[i], "left", this.oUl.offsetWidth / 2)
            }
            if (i < this.iCenter || i > this.iCenter) {
                this.css(this.aSort[i].getElementsByTagName("img")[0], "opacity", 30)
                
            } else {
              
            }
        }
      
        for(i = 0; i < this.aSort.length; i++){
            $(this.aSort[i].getElementsByTagName("a")[0]).attr("href","javacript:void(0);");
        }
        var href = $(this.aSort[2].getElementsByTagName("a")[0]).attr('data-href');
        $(this.aSort[2].getElementsByTagName("a")[0]).attr('href',href);
    },
    addEvent: function (oElement, sEventType, fnHandler) {
        return oElement.addEventListener ? oElement.addEventListener(sEventType, fnHandler, false) : oElement.attachEvent("on" + sEventType, fnHandler)
    },
    css: function (oElement, attr, value) {
        if (arguments.length == 2) {
            return oElement.currentStyle ? oElement.currentStyle[attr] : getComputedStyle(oElement, null)[attr]
        } else if (arguments.length == 3) {
            switch (attr) {
                case "width":
                case "height":
                case "top":
                case "left":
                case "bottom":
                    oElement.style[attr] = value + "px";
                    break;
                default:
                    oElement.style[attr] = value;
                    break
            }
        }
    },
    doMove: function (oElement, oAttr, fnCallBack) {
        var _this = this;
        clearInterval(oElement.timer);
        oElement.timer = setInterval(function () {
            var bStop = true;
            for (var property in oAttr) {
                var iCur = parseFloat(_this.css(oElement, property));
                property == "opacity" && (iCur = parseInt(iCur.toFixed(2) * 100));
                var iSpeed = (oAttr[property] - iCur) / 5;
                iSpeed = iSpeed > 0 ? Math.ceil(iSpeed) : Math.floor(iSpeed);
                if (iCur != oAttr[property]) {
                    bStop = false;
                    _this.css(oElement, property, iCur + iSpeed)
                }
            }
            if (bStop) {
                clearInterval(oElement.timer);
                fnCallBack && fnCallBack.apply(_this, arguments)
            }
        }, 30)
    }
};