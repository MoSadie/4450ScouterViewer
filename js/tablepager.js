/**
 * Created by Caleb Milligan on 3/22/2016.
 */

function Pager(selector) {
    this.selector = selector;
    Pager.pagers.push(this);
}

Pager.pagers = [];

Pager.getPager = function (selector) {
    var match = undefined;
    Pager.pagers.forEach(function (pager) {
        if (pager.selector == selector) {
            match = pager;
            return false;
        }
    });
    return match;
};

Pager.removePager = function (selector) {
    var match = undefined;
    Pager.pagers.forEach(function (pager) {
        if (pager.selector == selector) {
            match = pager;
            return false;
        }
    });
    if (match) {
        var index = Pager.pagers.indexOf(match);
        if (index > -1) {
            Pager.pagers.splice(index)
        }
    }
    return match;
};

Pager.prototype.selector = undefined;

Pager.prototype.getSelector = function () {
    return this.selector;
};

Pager.prototype.page_size = 30;

Pager.prototype.getPageSize = function () {
    return this.page_size;
};

Pager.prototype.setPageSize = function (page_size) {
    this.page_size = page_size;
    return this;
};

Pager.prototype.pages = [];

Pager.prototype.current_page = 0;

Pager.prototype.onPageChanged = undefined;

Pager.prototype.getPage = function () {
    return this.current_page;
};

Pager.prototype.firstPage = function () {
    if (this.getPage() != 0) {
        var old_page = this.getPage();
        this.setPage(0);
        if (this.onPageChanged) {
            this.onPageChanged(this.getPage(), old_page);
        }
    }
    return this;
};

Pager.prototype.prevPage = function () {
    if (this.getPage() > 0) {
        var old_page = this.getPage();
        this.setPage(this.getPage() - 1);
        if (this.onPageChanged) {
            this.onPageChanged(this.getPage(), old_page);
        }
    }
    return this;
};

Pager.prototype.setPage = function (page) {
    this.current_page = Math.min(Math.max(0, page), this.pages.length - 1);
    this.displayPage();
    return this;
};

Pager.prototype.inputPage = function (input) {
    var value = input.value;
    if (!value) {
        return this;
    }
    value = value.split("/")[0] - 1;
    if (isNaN(value)) {
        value = this.getPage();
    }
    if (value != this.getPage) {
        var old_page = this.getPage();
        this.setPage(value);
        if (this.onPageChanged) {
            this.onPageChanged(this.getPage(), old_page);
        }
    }
    return this;
};

Pager.prototype.nextPage = function () {
    if (this.getPage() < (this.pages.length - 1)) {
        this.setPage(this.getPage() + 1)
    }
    return this;
};

Pager.prototype.lastPage = function () {
    if (this.getPage() != (this.pages.length - 1)) {
        this.setPage(this.pages.length - 1);
    }
    return this;
};

Pager.prototype.displayPage = function () {
    var str = "";
    if (this.pages.length > 0) {
        this.pages[this.getPage()].forEach(function (value) {
            str += value.outerHTML;
        });
    }
    else {
        this.current_page = -1;
    }
    var pager = this;
    $(this.getSelector() + " tbody").each(function () {
        this.innerHTML = str;
    });
    $(this.selector + "-paginator_selector").each(function () {
        this.value = (pager.getPage() + 1) + "/" + (pager.pages.length);
    });
    return this;
};

Pager.prototype.paginate = function (do_padding) {
    var i = 0;
    var page = [];
    this.current_page = 0;
    this.pages = [];
    var full_page = false;
    var pager = this;
    $(this.getSelector() + " tbody tr").each(function () {
        page.push(this);
        full_page = false;
        if (i++ + 1 >= pager.getPageSize()) {
            pager.pages.push(page);
            page = [];
            i = 0;
            full_page = true;
        }
    });
    if (!full_page) {
        if (do_padding) {
            while (i < pager.getPageSize()) {
                i++;
                var row = document.createElement("tr");
                var element = document.createElement("td");
                element.innerHTML = "0";
                element.setAttribute("style", "border: none");
                row.appendChild(element);
                row.setAttribute("style", "visibility: hidden");
                page.push(row);
            }
        }
        this.pages.push(page);
    }
    $(this.getSelector() + " tbody").each(function () {
        this.innerHTML = "";
    });
    return this;
};