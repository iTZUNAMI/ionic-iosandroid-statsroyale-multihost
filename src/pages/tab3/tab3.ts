import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';

import { InAppBrowser } from '@ionic-native/in-app-browser';
import { DomSanitizer } from '@angular/platform-browser';
import { Platform } from 'ionic-angular';

//@IonicPage()
@Component({
  selector: 'page-tab3',
  templateUrl: 'tab3.html',
})
export class Tab3Page {

     

  constructor(public navCtrl: NavController, public navParams: NavParams,private iab: InAppBrowser,public sanitizer: DomSanitizer,private platform: Platform) {
  }

  ionViewDidLoad() {
    
  }

  mostraUrl(url){

    window.open(url,'_blank'); 

  }



}
