import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';

import { Platform } from 'ionic-angular';
import { DomSanitizer } from '@angular/platform-browser';


/**
 * Generated class for the TopPlayersPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-top-players',
  templateUrl: 'top-players.html',
})
export class TopPlayersPage {

  constructor(public navCtrl: NavController, public navParams: NavParams,private platform: Platform,public sanitizer: DomSanitizer) {
  }

  ionViewDidLoad() {
    
  }

   urlpagina() {
   
   
     return this.sanitizer.bypassSecurityTrustResourceUrl("https://statsroyale.com/top/players");	
    	
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
