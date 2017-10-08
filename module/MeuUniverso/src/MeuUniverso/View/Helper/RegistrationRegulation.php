<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/10/2017
 * Time: 10:44
 */

namespace MeuUniverso\View\Helper;


use Application\Entity\Registration\Registration;

class RegistrationRegulation
{
    public function __invoke(Registration $regulation)
    {
        $html = '
            <div class="portlet light bordered bg-inverse">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase">'.$regulation->getName().'</span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title="Expandir"> </a>
                    </div>
                </div>
                <div class="portlet-body movie-regulation">
                    <div class="scroller" style="height:300px" 
                        data-rail-visible="1" 
                        data-rail-color="yellow" 
                        data-handle-color="#a1b2bd">'.$regulation->getRegulation().'</div>
                </div>
            </div>';

        return $html;
    }
}