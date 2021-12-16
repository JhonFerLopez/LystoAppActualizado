!function (a, b, c) {
    "use strict";
    !function d(a, b, c) {
        function e(g, h) {
            if (!b[g]) {
                if (!a[g]) {
                    var i = "function" == typeof require && require;
                    if (!h && i)return i(g, !0);
                    if (f)return f(g, !0);
                    var j = new Error("Cannot find module '" + g + "'");
                    throw j.code = "MODULE_NOT_FOUND", j
                }
                var k = b[g] = {exports: {}};
                a[g][0].call(k.exports, function (b) {
                    var c = a[g][1][b];
                    return e(c ? c : b)
                }, k, k.exports, d, a, b, c)
            }
            return b[g].exports
        }

        for (var f = "function" == typeof require && require, g = 0; g < c.length; g++)e(c[g]);
        return e
    }({
        1: [function (a, b, c) {
            Object.defineProperty(c, "__esModule", {value: !0});
            var d = {
                title: "",
                text: "",
                type: null,
                allowOutsideClick: !1,
                showConfirmButton: !0,
                showCancelButton: !1,
                closeOnConfirm: !0,
                closeOnCancel: !0,
                confirmButtonText: "OK",
                confirmButtonClass: "btn-primary",
                cancelButtonText: "Cancel",
                cancelButtonClass: "btn-default",
                containerClass: "",
                titleClass: "",
                textClass: "",
                imageUrl: null,
                imageSize: null,
                timer: null,
                customClass: "",
                html: !1,
                animation: !0,
                allowEscapeKey: !0,
                inputType: "text",
                inputPlaceholder: "",
                inputValue: "",
                inputMin: "",
                showLoaderOnConfirm: !1
            };
            c["default"] = d
        }, {}], 2: [function (b, d, e) {
            Object.defineProperty(e, "__esModule", {value: !0}), e.handleCancel = e.handleConfirm = e.handleButton = c;
            var f = (b("./handle-swal-dom"), b("./handle-dom")), g = function (b, c, d) {
                var e, g, j, k = b || a.event, l = k.target || k.srcElement, m = -1 !== l.className.indexOf("confirm"),
                    n = -1 !== l.className.indexOf("sweet-overlay"), o = (0, f.hasClass)(d, "visible"),
                    p = c.doneFunction && "true" === d.getAttribute("data-has-done-function");
                switch (m && c.confirmButtonColor && (e = c.confirmButtonColor, g = colorLuminance(e, -.04), j = colorLuminance(e, -.14)), k.type) {
                    case"click":
                        var q = d === l, r = (0, f.isDescendant)(d, l);
                        if (!q && !r && o && !c.allowOutsideClick)break;
                        m && p && o ? h(d, c) : p && o || n ? i(d, c) : (0, f.isDescendant)(d, l) && "BUTTON" === l.tagName && sweetAlert.close()
                }
            }, h = function (a, b) {
                var c = !0;
                (0, f.hasClass)(a, "show-input") && (c = a.querySelector("input").value, c || (c = "")), b.doneFunction(c), b.closeOnConfirm && sweetAlert.close(), b.showLoaderOnConfirm && sweetAlert.disableButtons()
            }, i = function (a, b) {
                var c = String(b.doneFunction).replace(/\s/g, ""),
                    d = "function(" === c.substring(0, 9) && ")" !== c.substring(9, 10);
                d && b.doneFunction(!1), b.closeOnCancel && sweetAlert.close()
            };
            e.handleButton = g, e.handleConfirm = h, e.handleCancel = i
        }, {"./handle-dom": 3, "./handle-swal-dom": 5}], 3: [function (c, d, e) {
            Object.defineProperty(e, "__esModule", {value: !0});
            var f = function (a, b) {
                return new RegExp(" " + b + " ").test(" " + a.className + " ")
            }, g = function (a, b) {
                f(a, b) || (a.className += " " + b)
            }, h = function (a, b) {
                var c = " " + a.className.replace(/[\t\r\n]/g, " ") + " ";
                if (f(a, b)) {
                    for (; c.indexOf(" " + b + " ") >= 0;)c = c.replace(" " + b + " ", " ");
                    a.className = c.replace(/^\s+|\s+$/g, "")
                }
            }, i = function (a) {
                var c = b.createElement("div");
                return c.appendChild(b.createTextNode(a)), c.innerHTML
            }, j = function (a) {
                a.style.opacity = "", a.style.display = "block"
            }, k = function (a) {
                if (a && !a.length)return j(a);
                for (var b = 0; b < a.length; ++b)j(a[b])
            }, l = function (a) {
                a.style.opacity = "", a.style.display = "none"
            }, m = function (a) {
                if (a && !a.length)return l(a);
                for (var b = 0; b < a.length; ++b)l(a[b])
            }, n = function (a, b) {
                for (var c = b.parentNode; null !== c;) {
                    if (c === a)return !0;
                    c = c.parentNode
                }
                return !1
            }, o = function (a) {
                a.style.left = "-9999px", a.style.display = "block";
                var b, c = a.clientHeight;
                return b = "undefined" != typeof getComputedStyle ? parseInt(getComputedStyle(a).getPropertyValue("padding-top"), 10) : parseInt(a.currentStyle.padding), a.style.left = "", a.style.display = "none", "-" + parseInt((c + b) / 2) + "px"
            }, p = function (a, b) {
                if (+a.style.opacity < 1) {
                    b = b || 16, a.style.opacity = 0, a.style.display = "block";
                    var c = +new Date, d = function e() {
                        a.style.opacity = +a.style.opacity + (new Date - c) / 100, c = +new Date, +a.style.opacity < 1 && setTimeout(e, b)
                    };
                    d()
                }
                a.style.display = "block"
            }, q = function (a, b) {
                b = b || 16, a.style.opacity = 1;
                var c = +new Date, d = function e() {
                    a.style.opacity = +a.style.opacity - (new Date - c) / 100, c = +new Date, +a.style.opacity > 0 ? setTimeout(e, b) : a.style.display = "none"
                };
                d()
            }, r = function (c) {
                if ("function" == typeof MouseEvent) {
                    var d = new MouseEvent("click", {view: a, bubbles: !1, cancelable: !0});
                    c.dispatchEvent(d)
                } else if (b.createEvent) {
                    var e = b.createEvent("MouseEvents");
                    e.initEvent("click", !1, !1), c.dispatchEvent(e)
                } else b.createEventObject ? c.fireEvent("onclick") : "function" == typeof c.onclick && c.onclick()
            }, s = function (b) {
                "function" == typeof b.stopPropagation ? (b.stopPropagation(), b.preventDefault()) : a.event && a.event.hasOwnProperty("cancelBubble") && (a.event.cancelBubble = !0)
            };
            e.hasClass = f, e.addClass = g, e.removeClass = h, e.escapeHtml = i, e._show = j, e.show = k, e._hide = l, e.hide = m, e.isDescendant = n, e.getTopMargin = o, e.fadeIn = p, e.fadeOut = q, e.fireClick = r, e.stopEventPropagation = s
        }, {}], 4: [function (b, d, e) {
            Object.defineProperty(e, "__esModule", {value: !0});
            var f = b("./handle-dom"), g = b("./handle-swal-dom"), h = function (b, d, e) {
                var h = b || a.event, i = h.keyCode || h.which, j = e.querySelector("button.confirm"),
                    k = e.querySelector("button.cancel"), l = e.querySelectorAll("button[tabindex]");
                if (-1 !== [9, 13, 32, 27].indexOf(i)) {
                    for (var m = h.target || h.srcElement, n = -1, o = 0; o < l.length; o++)if (m === l[o]) {
                        n = o;
                        break
                    }
                    9 === i ? (m = -1 === n ? j : n === l.length - 1 ? l[0] : l[n + 1], (0, f.stopEventPropagation)(h), m.focus(), d.confirmButtonColor && (0, g.setFocusStyle)(m, d.confirmButtonColor)) : 13 === i ? ("INPUT" === m.tagName && (m = j, j.focus()), m = -1 === n ? j : c) : 27 === i && d.allowEscapeKey === !0 ? (m = k, (0, f.fireClick)(m, h)) : m = c
                }
            };
            e["default"] = h
        }, {"./handle-dom": 3, "./handle-swal-dom": 5}], 5: [function (d, e, f) {
            function g(a) {
                return a && a.__esModule ? a : {"default": a}
            }

            Object.defineProperty(f, "__esModule", {value: !0}), f.fixVerticalPosition = f.resetInputError = f.resetInput = f.openModal = f.getInput = f.getOverlay = f.getModal = f.sweetAlertInitialize = c;
            var h = d("./handle-dom"), i = d("./default-params"), j = g(i), k = d("./injected-html"), l = g(k),
                m = ".sweet-alert", n = ".sweet-overlay", o = function () {
                    var a = b.createElement("div");
                    for (a.innerHTML = l["default"]; a.firstChild;)b.body.appendChild(a.firstChild)
                }, p = function w() {
                    var a = b.querySelector(m);
                    return a || (o(), a = w()), a
                }, q = function () {
                    var a = p();
                    return a ? a.querySelector("input") : void 0
                }, r = function () {
                    return b.querySelector(n)
                }, s = function (c) {
                    var d = p();
                    (0, h.fadeIn)(r(), 10), (0, h.show)(d), (0, h.addClass)(d, "showSweetAlert"), (0, h.removeClass)(d, "hideSweetAlert"), a.previousActiveElement = b.activeElement;
                    var e = d.querySelector("button.confirm");
                    e.focus(), setTimeout(function () {
                        (0, h.addClass)(d, "visible")
                    }, 500);
                    var f = d.getAttribute("data-timer");
                    if ("null" !== f && "" !== f) {
                        var g = c;
                        d.timeout = setTimeout(function () {
                            var a = (g || null) && "true" === d.getAttribute("data-has-done-function");
                            a ? g(null) : sweetAlert.close()
                        }, f)
                    }
                }, t = function () {
                    var a = p(), b = q();
                    (0, h.removeClass)(a, "show-input"), b.value = j["default"].inputValue, b.min = j["default"].inputMin, b.setAttribute("type", j["default"].inputType), b.setAttribute("placeholder", j["default"].inputPlaceholder), u()
                }, u = function (a) {
                    if (a && 13 === a.keyCode)return !1;
                    var b = p(), c = b.querySelector(".sa-input-error");
                    (0, h.removeClass)(c, "show");
                    var d = b.querySelector(".form-group");
                    (0, h.removeClass)(d, "has-error")
                }, v = function () {
                    var a = p();
                    a.style.marginTop = (0, h.getTopMargin)(p())
                };
            f.sweetAlertInitialize = o, f.getModal = p, f.getOverlay = r, f.getInput = q, f.openModal = s, f.resetInput = t, f.resetInputError = u, f.fixVerticalPosition = v
        }, {"./default-params": 1, "./handle-dom": 3, "./injected-html": 6}], 6: [function (a, b, c) {
            Object.defineProperty(c, "__esModule", {value: !0});
            var d = '<div class="sweet-overlay" tabIndex="-1"></div><div class="sweet-alert" tabIndex="-1"><div class="sa-icon sa-error">\n      <span class="sa-x-mark">\n        <span class="sa-line sa-left"></span>\n        <span class="sa-line sa-right"></span>\n      </span>\n    </div><div class="sa-icon sa-warning">\n      <span class="sa-body"></span>\n      <span class="sa-dot"></span>\n    </div><div class="sa-icon sa-info"></div><div class="sa-icon sa-success">\n      <span class="sa-line sa-tip"></span>\n      <span class="sa-line sa-long"></span>\n\n      <div class="sa-placeholder"></div>\n      <div class="sa-fix"></div>\n    </div><div class="sa-icon sa-custom"></div><h2>Title</h2>\n    <p class="lead text-muted">Text</p>\n    <div class="form-group">\n      <input type="text" class="form-control" tabIndex="3" />\n      <span class="sa-input-error help-block">\n        <span class="glyphicon glyphicon-exclamation-sign"></span> <span class="sa-help-text">Not valid</span>\n      </span>\n    </div><div class="sa-button-container">\n      <button class="cancel btn btn-lg" tabIndex="2">Cancel</button>\n      <div class="sa-confirm-button-container">\n        <button class="confirm btn btn-lg" tabIndex="1">OK</button><div class="la-ball-fall">\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>\n    </div></div>';
            c["default"] = d
        }, {}], 7: [function (a, b, c) {
            Object.defineProperty(c, "__esModule", {value: !0});
            var d = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (a) {
                    return typeof a
                } : function (a) {
                    return a && "function" == typeof Symbol && a.constructor === Symbol ? "symbol" : typeof a
                }, e = a("./utils"), f = a("./handle-swal-dom"), g = a("./handle-dom"),
                h = ["error", "warning", "info", "success", "input", "prompt"], i = function (a) {
                    var b = (0, f.getModal)(), c = b.querySelector("h2"), i = b.querySelector("p"),
                        j = b.querySelector("button.cancel"), k = b.querySelector("button.confirm");
                    if (c.innerHTML = a.html ? a.title : (0, g.escapeHtml)(a.title).split("\n").join("<br>"), i.innerHTML = a.html ? a.text : (0, g.escapeHtml)(a.text || "").split("\n").join("<br>"), a.text && (0, g.show)(i), a.customClass) (0, g.addClass)(b, a.customClass), b.setAttribute("data-custom-class", a.customClass); else {
                        var l = b.getAttribute("data-custom-class");
                        (0, g.removeClass)(b, l), b.setAttribute("data-custom-class", "")
                    }
                    if ((0, g.hide)(b.querySelectorAll(".sa-icon")), a.type && !(0, e.isIE8)()) {
                        var m = function () {
                            for (var c = !1, d = 0; d < h.length; d++)if (a.type === h[d]) {
                                c = !0;
                                break
                            }
                            if (!c)return logStr("Unknown alert type: " + a.type), {v: !1};
                            var e = ["success", "error", "warning", "info"], i = void 0;
                            -1 !== e.indexOf(a.type) && (i = b.querySelector(".sa-icon.sa-" + a.type), (0, g.show)(i));
                            var j = (0, f.getInput)();
                            switch (a.type) {
                                case"success":
                                    (0, g.addClass)(i, "animate"), (0, g.addClass)(i.querySelector(".sa-tip"), "animateSuccessTip"), (0, g.addClass)(i.querySelector(".sa-long"), "animateSuccessLong");
                                    break;
                                case"error":
                                    (0, g.addClass)(i, "animateErrorIcon"), (0, g.addClass)(i.querySelector(".sa-x-mark"), "animateXMark");
                                    break;
                                case"warning":
                                    (0, g.addClass)(i, "pulseWarning"), (0, g.addClass)(i.querySelector(".sa-body"), "pulseWarningIns"), (0, g.addClass)(i.querySelector(".sa-dot"), "pulseWarningIns");
                                    break;
                                case"input":
                                case"prompt":
                                    j.setAttribute("type", a.inputType), j.value = a.inputValue, j.min = a.inputMin, j.setAttribute("placeholder", a.inputPlaceholder), (0, g.addClass)(b, "show-input"), setTimeout(function () {
                                        j.focus(), j.addEventListener("keyup", swal.resetInputError)
                                    }, 400)
                            }
                        }();
                        if ("object" === ("undefined" == typeof m ? "undefined" : d(m)))return m.v
                    }
                    if (a.imageUrl) {
                        var n = b.querySelector(".sa-icon.sa-custom");
                        n.style.backgroundImage = "url(" + a.imageUrl + ")", (0, g.show)(n);
                        var o = 80, p = 80;
                        if (a.imageSize) {
                            var q = a.imageSize.toString().split("x"), r = q[0], s = q[1];
                            r && s ? (o = r, p = s) : logStr("Parameter imageSize expects value with format WIDTHxHEIGHT, got " + a.imageSize)
                        }
                        n.setAttribute("style", n.getAttribute("style") + "width:" + o + "px; height:" + p + "px")
                    }
                    b.setAttribute("data-has-cancel-button", a.showCancelButton), a.showCancelButton ? j.style.display = "inline-block" : (0, g.hide)(j), b.setAttribute("data-has-confirm-button", a.showConfirmButton), a.showConfirmButton ? k.style.display = "inline-block" : (0, g.hide)(k), a.cancelButtonText && (j.innerHTML = (0, g.escapeHtml)(a.cancelButtonText)), a.confirmButtonText && (k.innerHTML = (0, g.escapeHtml)(a.confirmButtonText)), k.className = "confirm btn btn-lg", (0, g.addClass)(b, a.containerClass), (0, g.addClass)(k, a.confirmButtonClass), (0, g.addClass)(j, a.cancelButtonClass), (0, g.addClass)(c, a.titleClass), (0, g.addClass)(i, a.textClass), b.setAttribute("data-allow-outside-click", a.allowOutsideClick);
                    var t = !!a.doneFunction;
                    b.setAttribute("data-has-done-function", t), a.animation ? "string" == typeof a.animation ? b.setAttribute("data-animation", a.animation) : b.setAttribute("data-animation", "pop") : b.setAttribute("data-animation", "none"), b.setAttribute("data-timer", a.timer)
                };
            c["default"] = i
        }, {"./handle-dom": 3, "./handle-swal-dom": 5, "./utils": 8}], 8: [function (b, c, d) {
            Object.defineProperty(d, "__esModule", {value: !0});
            var e = function (a, b) {
                for (var c in b)b.hasOwnProperty(c) && (a[c] = b[c]);
                return a
            }, f = function () {
                return a.attachEvent && !a.addEventListener
            }, g = function (b) {
                a.console && a.console.log("SweetAlert: " + b)
            };
            d.extend = e, d.isIE8 = f, d.logStr = g
        }, {}], 9: [function (d, e, f) {
            function g(a) {
                return a && a.__esModule ? a : {"default": a}
            }

            Object.defineProperty(f, "__esModule", {value: !0});
            var h, i, j, k, l = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (a) {
                    return typeof a
                } : function (a) {
                    return a && "function" == typeof Symbol && a.constructor === Symbol ? "symbol" : typeof a
                }, m = d("./modules/handle-dom"), n = d("./modules/utils"), o = d("./modules/handle-swal-dom"),
                p = d("./modules/handle-click"), q = d("./modules/handle-key"), r = g(q),
                s = d("./modules/default-params"), t = g(s), u = d("./modules/set-params"), v = g(u);
            f["default"] = j = k = function () {
                function d(a) {
                    var b = e;
                    return b[a] === c ? t["default"][a] : b[a]
                }

                var e = arguments[0];
                if ((0, m.addClass)(b.body, "stop-scrolling"), (0, o.resetInput)(), e === c)return (0, n.logStr)("SweetAlert expects at least 1 attribute!"), !1;
                var f = (0, n.extend)({}, t["default"]);
                switch ("undefined" == typeof e ? "undefined" : l(e)) {
                    case"string":
                        f.title = e, f.text = arguments[1] || "", f.type = arguments[2] || "";
                        break;
                    case"object":
                        if (e.title === c)return (0, n.logStr)('Missing "title" argument!'), !1;
                        f.title = e.title;
                        for (var g in t["default"])f[g] = d(g);
                        f.confirmButtonText = f.showCancelButton ? "Confirm" : t["default"].confirmButtonText, f.confirmButtonText = d("confirmButtonText"), f.doneFunction = arguments[1] || null;
                        break;
                    default:
                        return (0, n.logStr)('Unexpected type of argument! Expected "string" or "object", got ' + ("undefined" == typeof e ? "undefined" : l(e))), !1
                }
                (0, v["default"])(f), (0, o.fixVerticalPosition)(), (0, o.openModal)(arguments[1]);
                for (var j = (0, o.getModal)(), q = j.querySelectorAll("button"), s = ["onclick"], u = function (a) {
                    return (0, p.handleButton)(a, f, j)
                }, w = 0; w < q.length; w++)for (var x = 0; x < s.length; x++) {
                    var y = s[x];
                    q[w][y] = u
                }
                (0, o.getOverlay)().onclick = u, h = a.onkeydown;
                var z = function (a) {
                    return (0, r["default"])(a, f, j)
                };
                a.onkeydown = z, a.onfocus = function () {
                    setTimeout(function () {
                        i !== c && (i.focus(), i = c)
                    }, 0)
                }, k.enableButtons()
            }, j.setDefaults = k.setDefaults = function (a) {
                if (!a)throw new Error("userParams is required");
                if ("object" !== ("undefined" == typeof a ? "undefined" : l(a)))throw new Error("userParams has to be a object");
                (0, n.extend)(t["default"], a)
            }, j.close = k.close = function () {
                var d = (0, o.getModal)();
                (0, m.fadeOut)((0, o.getOverlay)(), 5), (0, m.fadeOut)(d, 5), (0, m.removeClass)(d, "showSweetAlert"), (0, m.addClass)(d, "hideSweetAlert"), (0, m.removeClass)(d, "visible");
                var e = d.querySelector(".sa-icon.sa-success");
                (0, m.removeClass)(e, "animate"), (0, m.removeClass)(e.querySelector(".sa-tip"), "animateSuccessTip"), (0, m.removeClass)(e.querySelector(".sa-long"), "animateSuccessLong");
                var f = d.querySelector(".sa-icon.sa-error");
                (0, m.removeClass)(f, "animateErrorIcon"), (0, m.removeClass)(f.querySelector(".sa-x-mark"), "animateXMark");
                var g = d.querySelector(".sa-icon.sa-warning");
                return (0, m.removeClass)(g, "pulseWarning"), (0, m.removeClass)(g.querySelector(".sa-body"), "pulseWarningIns"), (0, m.removeClass)(g.querySelector(".sa-dot"), "pulseWarningIns"), setTimeout(function () {
                    var a = d.getAttribute("data-custom-class");
                    (0, m.removeClass)(d, a)
                }, 300), (0, m.removeClass)(b.body, "stop-scrolling"), a.onkeydown = h, a.previousActiveElement && a.previousActiveElement.focus(), i = c, clearTimeout(d.timeout), !0
            }, j.showInputError = k.showInputError = function (a) {
                var b = (0, o.getModal)(), c = b.querySelector(".sa-input-error");
                (0, m.addClass)(c, "show");
                var d = b.querySelector(".form-group");
                (0, m.addClass)(d, "has-error"), d.querySelector(".sa-help-text").innerHTML = a, setTimeout(function () {
                    j.enableButtons()
                }, 1), b.querySelector("input").focus()
            }, j.resetInputError = k.resetInputError = function (a) {
                if (a && 13 === a.keyCode)return !1;
                var b = (0, o.getModal)(), c = b.querySelector(".sa-input-error");
                (0, m.removeClass)(c, "show");
                var d = b.querySelector(".form-group");
                (0, m.removeClass)(d, "has-error")
            }, j.disableButtons = k.disableButtons = function (a) {
                var b = (0, o.getModal)(), c = b.querySelector("button.confirm"), d = b.querySelector("button.cancel");
                c.disabled = !0, d.disabled = !0
            }, j.enableButtons = k.enableButtons = function (a) {
                var b = (0, o.getModal)(), c = b.querySelector("button.confirm"), d = b.querySelector("button.cancel");
                c.disabled = !1, d.disabled = !1
            }, "undefined" != typeof a ? a.sweetAlert = a.swal = j : (0, n.logStr)("SweetAlert is a frontend module!")
        }, {
            "./modules/default-params": 1,
            "./modules/handle-click": 2,
            "./modules/handle-dom": 3,
            "./modules/handle-key": 4,
            "./modules/handle-swal-dom": 5,
            "./modules/set-params": 7,
            "./modules/utils": 8
        }]
    }, {}, [9]), "function" == typeof define && define.amd ? define(function () {
        return sweetAlert
    }) : "undefined" != typeof module && module.exports && (module.exports = sweetAlert)
}(window, document);