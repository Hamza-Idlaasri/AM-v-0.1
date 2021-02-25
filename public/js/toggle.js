/********** Toggling the sidebar menu *********+*/

const togleOn = document.querySelector('.toggle-on');
const togleOff = document.querySelector('.toggle-off');

const sidebar = document.querySelector('.sidebar');

togleOn.onclick = () => {

    sidebar.style.left = '-220px';
    sidebar.style.width = '0px';
    togleOff.style.transitionDelay = ".2s";
    togleOff.style.visibility = 'visible';


};

togleOff.onclick = () => {

    sidebar.style.left = '0px';
    sidebar.style.width = '220px';
    togleOff.style.transitionDelay = "0s";
    togleOff.style.visibility = 'hidden';
};

function droped() {

    togleOn.classList.toggle('hidden');
    togleOn.style.transitionDelay = '0s';

    if (!togleOn.classList.contains('hidden'))
        togleOn.style.transitionDelay = '.3s';

    if (sidebar.clientHeight == sidebar.scrollHeight) {
        console.log('pas scroll');
    }

}

/*************** Make the Accordion effect *************/

$(function() {
    var Accordion = function(el, multiple) {
        this.el = el || {};
        // more then one submenu open?
        this.multiple = multiple || false;

        var dropdownlink = this.el.find('.dropdownlink');
        dropdownlink.on('click', {
                el: this.el,
                multiple: this.multiple
            },
            this.dropdown);
    };

    Accordion.prototype.dropdown = function(e) {
        var $el = e.data.el,
            $this = $(this),
            //this is the ul.submenuItems
            $next = $this.next();

        $next.slideToggle();
        $this.parent().toggleClass('open');

        if (!e.data.multiple) {
            //show only one menu at the same time
            $el.find('.submenuItems').not($next).slideUp().parent().removeClass('open');
        }
    }

    var accordion = new Accordion($('.accordion-menu'), false);
})