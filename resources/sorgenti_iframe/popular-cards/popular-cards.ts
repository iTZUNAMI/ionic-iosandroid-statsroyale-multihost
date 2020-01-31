import { Component } from '@angular/core';
import {  NavController, NavParams } from 'ionic-angular';

import { Platform } from 'ionic-angular';
import { DomSanitizer } from '@angular/platform-browser';


/**
 * Generated class for the PopularCardsPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
//@IonicPage()
@Component({
  selector: 'page-popular-cards',
  templateUrl: 'popular-cards.html',
})
export class PopularCardsPage {

  constructor(public navCtrl: NavController, public navParams: NavParams,private platform: Platform,public sanitizer: DomSanitizer) {
  }

  urlpagina() {
   
   
     return this.sanitizer.bypassSecurityTrustResourceUrl("https://statsroyale.com/top/cards");	
    	
	}

  //se android scrolling=yes se ios scrolling = no (forzato per problama ios safari mobile iframe!!!) + trick css 
  sino(){

    if(this.platform.is('ios')) {
    return "no";
    } else if (this.platform.is('android')) {
    return "yes";
    }

  }
}
