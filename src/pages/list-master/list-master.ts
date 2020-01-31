import { Component } from '@angular/core';
import { NavController, ModalController } from 'ionic-angular';

import { ItemCreatePage } from '../item-create/item-create';
import { ItemDetailPage } from '../item-detail/item-detail';

import { Items } from '../../providers/providers';
import { Item } from '../../models/item';

import { AdMob } from '@ionic-native/admob';

import { Platform } from 'ionic-angular';

import { AlertController } from 'ionic-angular';
import { Storage } from '@ionic/storage';

import { TranslateService } from '@ngx-translate/core';
import { SocialSharing } from '@ionic-native/social-sharing';

import { ToastController } from 'ionic-angular';

import { Http } from '@angular/http';


@Component({
  selector: 'page-list-master',
  templateUrl: 'list-master.html'
})
export class ListMasterPage {
  currentItems: Item[];

  //=0 no admob (for dev)
  admobdev=1;
  admobid: any;
  admobcounter : any = 0;
  admobresto : any = 0;
  //isTesting: true commentato sotto

  //mie impostazioni da remoto 0 = no 1 = si mostra
  admob_json_banner = 0;
  admob_json_video = 1;
  news = 0;
  news_message = "";


  constructor(public navCtrl: NavController, public items: Items, public modalCtrl: ModalController,private admob: AdMob,private platform: Platform,public alertCtrl: AlertController
  ,private storage: Storage,private socialSharing: SocialSharing,private translate: TranslateService,public toastCtrl: ToastController, public http: Http) {
   
    this.currentItems = this.items.query();


    //richiamo contatore (se >10 parte admob x sempre)
    this.storage.get('admobcounter').then((admobcounter) => {
      this.admobcounter=admobcounter;

    
      if (this.admobcounter == null || this.admobcounter == 0)
        { 
        // se contatore = 0 = nuovo utente allora mostro anche il toast con tasto OK nuovo player
       let mess=this.translate.instant("LIST_TOAST");
          let toast = this.toastCtrl.create({
            message: mess,
            position: 'top',
            showCloseButton : true,
            closeButtonText : 'OK',
            dismissOnPageChange : false
          });
          toast.present();
  
  
    }

    });

    //serve per attivare o disattivare admob con parola chiave per me
     this.storage.get('admobdev').then((admobdev) => {
      this.admobdev=admobdev;
    });





      //admob settings

       if(this.platform.is('android')) { // for Android

          this.admobid = {
          banner : 'ca-app-pub-7256570709641827/9846374864',  
          interstitial: 'ca-app-pub-7256570709641827/3355952597'
          }

       } else if (this.platform.is('ios')) {// for iOS
      
          this.admobid = {
          banner : 'ca-app-pub-7256570709641827/2734295314',  
          interstitial: 'ca-app-pub-7256570709641827/6309418998'
          }
      } 

       //admob setting, prendo da mio json se mostrare o meno

    this.http.get('http://itzunami.net/chestroyale/ads.php')
    .map(res => res.json()).subscribe
    (data => {
     
      if (data!=null){
        this.admob_json_banner= data.banner;
        this.admob_json_video= data.video;
        this.news = data.news;
        this.news_message = data.news_message



          //mostro mio messagio
          if (this.news==1){
        
          let toast = this.toastCtrl.create({
            message: this.news_message,
            position: 'top',
            showCloseButton : true,
            closeButtonText : 'OK',
            dismissOnPageChange : false
          });
          toast.present();
        }



      }
    

     // console.log("mobdev",this.admobdev);
     // console.log("counter",this.admobcounter);
     // console.log("jsonban",this.admob_json_banner);
      if (this.admobdev!=0 && this.admobcounter >= 0 && this.admob_json_banner==1){
      //  console.log("mostro banner");
       
          this.admob.createBanner({
            adId: this.admobid.banner,
            position : this.admob.AD_POSITION.BOTTOM_CENTER,
      //  isTesting: true,//comment this out before publishing the app
            autoShow: true

      });
     }

  });
     
     //lo preparo e avvio quando richiesto
     this.admob.prepareInterstitial({
                    adId: this.admobid.interstitial,
                   // isTesting: true, //comment this out before publishing the app
                    autoShow: false
                  });
                
   


}

  /**
   * The view loaded, let's query our items for the list
   */
    ionViewDidLoad() {
    this.admob.onAdDismiss()
      .subscribe(() => {
        
        // console.log('User dismissed ad');
    
    //se skippa potrei richiamare il prepare per rimostrarlo, però diventa app fastidiosa 
    //quindi lo metto sotto ogni multiplo di 10 del contatore ha più senso
       this.admob.prepareInterstitial({
                    adId: this.admobid.interstitial,
                   // isTesting: true, //comment this out before publishing the app
                    autoShow: false
                  });
                
                 
 });

 

  }


  /**
   * Prompt the user to add a new item. This shows our ItemCreatePage in a
   * modal and then adds the new item to our data source if the user created one.
   */
  addItem() {
    let addModal = this.modalCtrl.create(ItemCreatePage);
    addModal.onDidDismiss(item => {
      if (item) {

        
        //faccio replace se #tag presente per evitare casini
        item.tag=item.tag.replace("#","");

 

        //su input nome abilito pubblicità per me o no
        if (item.name=="admoboff"){
          this.admobdev=0;
          this.storage.set('admobdev',this.admobdev);
         // console.log("admob off");
        }
         if (item.name=="admobon"){
          this.admobdev=1;
          this.storage.set('admobdev',this.admobdev);
         //  console.log("admob on");
        }


        this.items.add(item);
      }
    })
    addModal.present();
  }

  /**
   * Delete an item from the list of items.
   */
  deleteItem(item) {
    this.items.delete(item);
    //devo fare caso item default eliminato
  }

  /**
   * Navigate to the detail page for this item.
   */
  openItem(item: Item) {


    

    //se attivo
    //-console.log("admobdev",this.admobdev);
    if (this.admobdev!=0)
    {


            //  console.log("admob attivo");
            
              //se maggiore di 10 volte questo passaggio per la prima volta
              if (this.admobcounter >= 0 && this.admob_json_video==1){

              //poi mostro ogni tot
              //ad esempio ogni 6 aperture
              this.admobresto = this.admobcounter%6;
              if (this.admobresto==0){

              //  console.log("multiplo 10");
              //qua lo chiama solo una volta avvio app    
              //  console.log("admob avviato");      
                this.avviaInterstitial();
              //  console.log("mostro video");
              }
              
              
              } 

        


        this.admobcounter++;
        this.storage.set('admobcounter',this.admobcounter);
        //salvo val default che sara 1
        //se = 0 non invoco adbmob
        this.storage.set('admobdev',this.admobdev);
      
      
    }


     //mostro dettagli
     this.navCtrl.push(ItemDetailPage, {
      item: item
    });
    
   
  }


    //admob
    avviaInterstitial() {
     
        if (this.admob)
          { 
           this.admob.showInterstitial();
           
            // this.showAlert();
          }
    }

     showAlert() {
          let alert = this.alertCtrl.create({
            title: 'OK',
            subTitle: this.admobid.interstitial,
            buttons: ['OK']
          });
          alert.present();
        }


  shareYoutuber(channel){
    this.socialSharing.share(this.translate.instant("SHARE_NOME") + " " + this.translate.instant("SHARE_YOUTUBER"), null, "www/assets/img/share/icona.png", channel)
  }

  share(tag){
    let url="http://itzunami.net/chestroyale/share/?t=";
     this.socialSharing.share(this.translate.instant("SHARE_NOME") + " " + this.translate.instant("SHARE_ALL"), null, "www/assets/img/share/icona.png", url+tag)
  }




}
