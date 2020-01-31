import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';

import { Http } from '@angular/http';
import { LoadingController } from 'ionic-angular';

import { Item } from '../../models/item';
import { ItemDetailPage } from '../item-detail/item-detail';

//@IonicPage()
@Component({
  selector: 'page-tab1',
  templateUrl: 'tab1.html',
})
export class Tab1Page {

  posts: any;
  clan : any []; 

  questo: Item[];

  constructor(public navCtrl: NavController, public navParams: NavParams,public http: Http,public loadingCtrl: LoadingController) {
  

  }

  ionViewDidLoad() {
   
    let loading = this.loadingCtrl.create({
      spinner: 'dots',
      content: ``,
      duration: 5000
    });

    loading.onDidDismiss(() => {
    //  console.log('Dismissed loading');
    });

loading.present();

this.http.get('http://itzunami.net/chestroyale/clan.php').map(res => res.json()).subscribe(data => {

    //stoppo loading  
   loading.dismiss();

   this.posts = data; 

   //arrary di oggetti con info utenti
   this.clan = this.posts.clan;

 // console.log(this.posts);
  
});

  }

  livellotab1(numero){
    return 'assets/img/details/'+numero+'.png';
  }

  clanUtente(nome){
    if (nome==0)
      return "No Clan";
    return this.sistemaHtml(nome);
  }

    sistemaHtml(nome)
  {
    let m:String = nome;
    m = m.replace('&lt;','<').replace('&gt;', '>');
    m = m.replace ('&#039;','');
   
    
    return m;
  }

  openItemClan(tag){

    
    let questo =  
    {
        "name": tag,
        "tag": tag
     };
    

    let mostra = new Item (questo) ;

     this.navCtrl.push(ItemDetailPage, {
      item: mostra
    });
  }

   

}
