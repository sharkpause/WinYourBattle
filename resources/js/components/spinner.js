import $ from 'jquery';

export default function showSpinner(parentElem) {
    $(parentElem).find('.spinner-elem').css('visibility', 'visible');
}