import $ from 'jquery';

$("textarea").each(function () {
    this.style.height = this.scrollHeight + "px";
    this.style.overflowY = "hidden";
}).on("input", function () {
    this.style.height = "auto";
    this.style.height = this.scrollHeight + "px";
});