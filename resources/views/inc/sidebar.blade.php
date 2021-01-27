
<ul class="accordion-menu">
    <li>
        <div class="dropdownlink"><img src="{{asset('images/interface/eye.png')}}" alt=""><i class="fa fa-road" aria-hidden="true"></i><b> Monitoring</b>
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
        </div>
        <ul class="submenuItems">
            <li>
                <a href="/monitoring/hosts"><img src="{{asset('images/interface/Hosts.png')}}" alt=""> Hosts</a>
            </li>
            <li>
                <a href="/monitoring/services" ><img src="{{asset('images/interface/settings-(1).png')}}" alt=""> Services</a>
            </li>
            <li>
                <a href="/monitoring/boxs"><img src="{{asset('images/interface/Boxs.png')}}" alt=""> Boxs</a>
            </li>
            <li>
                <a href="/monitoring/equipements"><img src="{{asset('images/interface/Equipement.png')}}" alt=""> Equipements</a>
            </li>
            <li>
                <a href="/monitoring/problems"><img src="{{asset('images/interface/Problemes.png')}}" alt=""> Problémes</a>
            </li>
        </ul>
    </li>
    <li>
        <div class="dropdownlink"><img src="{{asset('images/interface/config.png')}}" alt=""><i class="fa fa-paper-plane" aria-hidden="true"></i><b> Configuration</b>
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
        </div>
        <ul class="submenuItems">
            <li>
                <a href="#"><img src="{{asset('images/interface/hostgroup.png')}}" alt=""> HostGroups</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/Hosts.png')}}" alt=""> Hosts</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/settings-(1).png')}}" alt=""> Services</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/sites.png')}}" alt=""> Sites</a>
            </li>
            <li><a href="#">Lieux</a></li>
            <li>
                <a href="#"><img src="{{asset('images/interface/equipgroup.png')}}" alt=""> EquipGroups</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/Equipement.png')}}" alt=""> Equipements</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/Boxs.png')}}" alt=""> Boxs</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/pins.png')}}" alt=""> Pins</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/users.png')}}" alt=""> Users</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/Problemes.png')}}" alt=""> Alarmes</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/commands.png')}}" alt=""> Commandes</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/notif.png')}}" alt=""> Notifications</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/servicegroup.png')}}" alt=""> ServiceGroups</a>
            </li>
        </ul>
    </li>
    <li>
        <div class="dropdownlink"><img src="{{asset('images/interface/chart.png')}}" alt=""><i class="fa fa-quote-left" aria-hidden="true"></i><b> Statistiques</b>
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
        </div>
        <ul class="submenuItems">
            <li>
                <a href="#"><img src="{{asset('images/interface/Hosts.png')}}" alt=""> Hosts</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/settings-(1).png')}}" alt=""> Services</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/Equipement.png')}}" alt=""> Equipements</a>
            </li>
        </ul>
    </li>
    <li>
        <div class="dropdownlink"><img src="{{asset('images/interface/cartes.png')}}" alt=""><i class="fa fa-motorcycle" aria-hidden="true"></i><b> Cartes</b>
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
        </div>
        <ul class="submenuItems">
            <li>
                <a href="#"><img src="{{asset('images/interface/automap.png')}}" alt=""> AutoMap</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/automap.png')}}" alt=""> AutoMap 2</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/automap.png')}}" alt=""> AutoMap 3</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/map.png')}}" alt=""> Cartes</a>
            </li>
        </ul>
    </li>
    <li>
        <div class="dropdownlink"><img src="{{asset('images/interface/calendar.png')}}" alt=""><i class="fa fa-quote-left" aria-hidden="true"></i><b> Historiques</b>
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
        </div>
        <ul class="submenuItems">
            <li>
                <a href="#"><img src="{{asset('images/interface/Hosts.png')}}" alt=""> Hosts</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/settings-(1).png')}}" alt=""> Services</a>
            </li>
            <li>
                <a href="#"><img src="{{asset('images/interface/Equipement.png')}}" alt=""> Equipements</a>
            </li>
        </ul>
    </li>
</ul>



<script>
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
</script>
