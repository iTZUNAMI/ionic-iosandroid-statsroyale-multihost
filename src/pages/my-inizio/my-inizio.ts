import { Component } from '@angular/core';
import {  NavController, NavParams } from 'ionic-angular';


import { TabsPage } from '../tabs/tabs';
import { Storage } from '@ionic/storage';
import { TutorialPage } from '../tutorial/tutorial';
import { SplashScreen } from '@ionic-native/splash-screen';
import { Item } from '../../models/item';
import { TranslateService } from '@ngx-translate/core'

//@IonicPage()
@Component({
  selector: 'page-my-inizio',
  templateUrl: 'my-inizio.html',
})
export class MyInizioPage {

    items: Item[] = [];
    

  constructor(public navCtrl: NavController, public navParams: NavParams, private storage: Storage, private spash: SplashScreen,public translate: TranslateService) {





  }



  ionViewDidLoad() {

//  this.storage.clear();

      this.spash.hide();

      console.log('ionViewDidLoad MyInizioPage');

      this.storage.get('skippato').then((valskippato) => {

        if (valskippato == true){

          console.log('Tutorial Skippato');

          //avvio app senza tutorial e non creo db default che avrò già creato la prima volta
          this.startAppNoAnimate();
        }
        else{

          //creo items di default in base alla lingua
          let items_lang : any[] = [];
          let items_endef : any[]= [];


          //per ita
          if (this.translate.currentLang == "it"){

             items_lang = [
                   {
                    "name": "CiccioGamer89",
                    "profilePic": "assets/img/avatar_def/ciccio.jpg",
                    "tag": "JL8RPC0",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/user/CiccioGamer89"
                  },
                  {
                    "name": "St3pNy",
                    "profilePic": "assets/img/avatar_def/st3pny.jpg",
                    "tag": "RULY0LY",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/user/MoD3rNSt3pNy"
                  },
                    {
                    "name": "DragonSteak TV",
                    "profilePic": "assets/img/avatar_def/dragon.jpg",
                    "tag": "22J8J8U",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/channel/UCKI91MuLfEw8H9MUEnMirwQ"
                  },
                    {
                    "name": "Anima",
                    "profilePic": "assets/img/avatar_def/anima.jpg",
                    "tag": "PRLQ9LLQ",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/user/MoD3rNSt3pNy"
                  },
                    {
                    "name": "Grax",
                    "profilePic": "assets/img/avatar_def/grax.jpg",
                    "tag": "JJYP08",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/channel/UCD1iDvLrcf0RwYOM_3_FIbA"
                  },

                  {
                    "name": "nickatnyte",
                    "profilePic": "assets/img/avatar_def/nickatnyte.jpg",
                    "tag": "YRRG",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/user/teachboombeach"
                  },
                  {
                    "name": "MOLT",
                    "profilePic": "assets/img/avatar_def/molt.jpg",
                    "tag": "PCV8",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/user/GAMINGwithMOLT"
                  },
                  



                ];


         for (let item of items_lang) {
            this.items.push(new Item(item));
          }
          }

          //per tutti in generale
          else{

             items_endef = [
                
                   {
                    "name": "nickatnyte",
                    "profilePic": "assets/img/avatar_def/nickatnyte.jpg",
                    "tag": "YRRG",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/user/teachboombeach"
                  },
                  {
                    "name": "MOLT",
                    "profilePic": "assets/img/avatar_def/molt.jpg",
                    "tag": "PCV8",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/user/GAMINGwithMOLT"
                  },
                  {
                    "name": "Chief Pat",
                    "profilePic": "assets/img/avatar_def/chef.jpg",
                    "tag": "P08P",
                    "youtube": "true",
                    "youtubechannel": "https://www.youtube.com/user/PlayClashOfClans"
                  },
                   

                ];

                 for (let item of items_endef) {
            this.items.push(new Item(item));
          }
          
          }


         


         
           //savlo
           this.storage.set('items',this.items);
                 


          //avvio tutorial
          this.startTutorial();
        }

      });

  }


   //imposto skippato = true in modo che la prossima volta salta il tutorial diretto
  //invocato da Salta tutorial
  startAppNoAnimate() {
  this.navCtrl.setRoot(TabsPage, {}, {
      animate: false,
      direction: 'forward'
    });
  }

  startTutorial(){
  this.navCtrl.setRoot(TutorialPage, {}, {
      animate: false,
      direction: 'forward'
    });

  }


}
