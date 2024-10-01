(function(global) {
    'use strict';

    var jBasic = function(selector) {
        if (!(this instanceof jBasic)) {
            return new jBasic(selector);
        }

        this.elements = document.querySelectorAll(selector);
    };

    jBasic.prototype.each = function(callback) {
        Array.prototype.forEach.call(this.elements, callback);
        return this;
    };

    // CSS Manipulation
    jBasic.prototype.addClass = function(className) {
        return this.each(function(element) {
            element.classList.add(className);
        });
    };

    jBasic.prototype.removeClass = function(className) {
        return this.each(function(element) {
            element.classList.remove(className);
        });
    };

    jBasic.prototype.toggleClass = function(className) {
        return this.each(function(element) {
            element.classList.toggle(className);
        });
    };

    jBasic.prototype.hasClass = function(className) {
        return Array.prototype.some.call(this.elements, function(element) {
            return element.classList.contains(className);
        });
    };

    jBasic.prototype.attr = function(name, value) {
        if (value === undefined) {
            return this.elements[0].getAttribute(name);
        } else {
            return this.each(function(element) {
                element.setAttribute(name, value);
            });
        }
    };

    jBasic.prototype.text = function(value) {
        if (value === undefined) {
            return this.elements[0].textContent;
        } else {
            return this.each(function(element) {
                element.textContent = value;
            });
        }
    };

    jBasic.prototype.html = function(value) {
        if (value === undefined) {
            return this.elements[0].innerHTML;
        } else {
            return this.each(function(element) {
                element.innerHTML = value;
            });
        }
    };

    jBasic.prototype.css = function(property, value) {
        return this.each(function(element) {
            element.style[property] = value;
        });
    };

    // Event Handling
    jBasic.prototype.on = function(event, handler) {
        return this.each(function(element) {
            element.addEventListener(event, handler, false);
        });
    };

    jBasic.prototype.off = function(event, handler) {
        return this.each(function(element) {
            element.removeEventListener(event, handler, false);
        });
    };

    jBasic.prototype.trigger = function(event) {
        var evt = new Event(event);
        return this.each(function(element) {
            element.dispatchEvent(evt);
        });
    };

    // Effects and Animation
    jBasic.prototype.hide = function() {
        return this.each(function(element) {
            element.style.display = 'none';
        });
    };

    jBasic.prototype.show = function() {
        return this.each(function(element) {
            element.style.display = '';
        });
    };

    jBasic.prototype.fadeIn = function(duration) {
        duration = duration || 400;
        return this.each(function(element) {
            element.style.opacity = 0;
            element.style.display = '';

            var last = +new Date();
            var tick = function() {
                element.style.opacity = +element.style.opacity + (new Date() - last) / duration;
                last = +new Date();

                if (+element.style.opacity < 1) {
                    (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
                }
            };

            tick();
        });
    };

    jBasic.prototype.fadeOut = function(duration) {
        duration = duration || 400;
        return this.each(function(element) {
            element.style.opacity = 1;

            var last = +new Date();
            var tick = function() {
                element.style.opacity = +element.style.opacity - (new Date() - last) / duration;
                last = +new Date();

                if (+element.style.opacity > 0) {
                    (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
                } else {
                    element.style.display = 'none';
                }
            };

            tick();
        });
    };

    jBasic.prototype.slideUp = function(duration) {
        duration = duration || 400;
        return this.each(function(element) {
            element.style.height = element.offsetHeight + 'px';
            element.style.transitionProperty = 'height, margin, padding';
            element.style.transitionDuration = duration + 'ms';
            element.style.height = 0;
            element.style.paddingTop = 0;
            element.style.paddingBottom = 0;
            element.style.marginTop = 0;
            element.style.marginBottom = 0;

            setTimeout(function() {
                element.style.display = 'none';
            }, duration);
        });
    };

    jBasic.prototype.slideDown = function(duration) {
        duration = duration || 400;
        return this.each(function(element) {
            element.style.display = '';
            var height = element.offsetHeight;
            element.style.height = 0;
            element.style.paddingTop = 0;
            element.style.paddingBottom = 0;
            element.style.marginTop = 0;
            element.style.marginBottom = 0;

            setTimeout(function() {
                element.style.height = height + 'px';
                element.style.paddingTop = '';
                element.style.paddingBottom = '';
                element.style.marginTop = '';
                element.style.marginBottom = '';
            }, 16);

            setTimeout(function() {
                element.style.height = '';
                element.style.transitionProperty = '';
                element.style.transitionDuration = '';
            }, duration);
        });
    };

    // DOM Traversal
    jBasic.prototype.find = function(selector) {
        var foundElements = [];
        this.each(function(element) {
            var nodes = element.querySelectorAll(selector);
            foundElements = foundElements.concat(Array.prototype.slice.call(nodes));
        });
        this.elements = foundElements;
        return this;
    };

    jBasic.prototype.parent = function() {
        var parents = [];
        this.each(function(element) {
            if (element.parentNode) {
                parents.push(element.parentNode);
            }
        });
        this.elements = parents;
        return this;
    };

    jBasic.prototype.parents = function() {
        var parents = [];
        this.each(function(element) {
            var parent = element.parentNode;
            while (parent) {
                parents.push(parent);
                parent = parent.parentNode;
            }
        });
        this.elements = parents;
        return this;
    };

    jBasic.prototype.children = function() {
        var children = [];
        this.each(function(element) {
            children = children.concat(Array.prototype.slice.call(element.children));
        });
        this.elements = children;
        return this;
    };

    jBasic.prototype.next = function() {
        var nextElements = [];
        this.each(function(element) {
            if (element.nextElementSibling) {
                nextElements.push(element.nextElementSibling);
            }
        });
        this.elements = nextElements;
        return this;
    };

    jBasic.prototype.prev = function() {
        var prevElements = [];
        this.each(function(element) {
            if (element.previousElementSibling) {
                prevElements.push(element.previousElementSibling);
            }
        });
        this.elements = prevElements;
        return this;
    };

    jBasic.prototype.siblings = function() {
        var siblings = [];
        this.each(function(element) {
            var sibling = element.parentNode.firstChild;
            while (sibling) {
                if (sibling.nodeType === 1 && sibling !== element) {
                    siblings.push(sibling);
                }
                sibling = sibling.nextSibling;
            }
        });
        this.elements = siblings;
        return this;
    };

    // DOM Manipulation
    jBasic.prototype.append = function(content) {
        return this.each(function(element) {
            if (typeof content === 'string') {
                element.insertAdjacentHTML('beforeend', content);
            } else if (content instanceof HTMLElement) {
                element.appendChild(content);
            }
        });
    };

    jBasic.prototype.prepend = function(content) {
        return this.each(function(element) {
            if (typeof content === 'string') {
                element.insertAdjacentHTML('afterbegin', content);
            } else if (content instanceof HTMLElement) {
                element.insertBefore(content, element.firstChild);
            }
        });
    };

    jBasic.prototype.remove = function() {
        return this.each(function(element) {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        });
    };

    jBasic.prototype.empty = function() {
        return this.each(function(element) {
            element.innerHTML = '';
        });
    };

    // Data Attributes
    jBasic.prototype.data = function(key, value) {
        if (value === undefined) {
            return this.elements[0].dataset[key];
        } else {
            return this.each(function(element) {
                element.dataset[key] = value;
            });
        }
    };

    // Utilities
    jBasic.each = function(collection, callback) {
        Array.prototype.forEach.call(collection, callback);
    };

    jBasic.map = function(collection, callback) {
        return Array.prototype.map.call(collection, callback);
    };

    jBasic.grep = function(array, callback) {
        return array.filter(callback);
    };

    jBasic.inArray = function(item, array) {
        return array.indexOf(item) !== -1;
    };

    // Selectors
    jBasic.prototype.filter = function(selector) {
        var filteredElements = Array.prototype.filter.call(this.elements, function(element) {
            return element.matches(selector);
        });
        this.elements = filteredElements;
        return this;
    };

    // AJAX
    jBasic.ajax = function(options) {
        var xhr = new XMLHttpRequest();
        xhr.open(options.type || 'GET', options.url, options.async !== false);
    
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var response = xhr.responseText;
    
                    // Kiểm tra nếu dữ liệu trả về là JSON và phân tích cú pháp
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        console.warn('Response is not JSON:', response);
                    }
    
                    if (options.success) {
                        options.success(response, xhr.status, xhr);
                    }
                } else {
                    if (options.error) {
                        options.error(xhr, xhr.status, xhr.statusText);
                    }
                }
            }
        };
    
        if (options.headers) {
            for (var header in options.headers) {
                xhr.setRequestHeader(header, options.headers[header]);
            }
        }
    
        if (options.beforeSend) {
            options.beforeSend(xhr);
        }
    
        xhr.send(options.data || null);
    };

    // Attach to global
    global.$ = jBasic;

}(window));