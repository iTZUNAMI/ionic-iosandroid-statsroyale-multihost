import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';
import { DomSanitizer } from '@angular/platform-browser';


import { Items } from '../../providers/providers';
import { Platform } from 'ionic-angular';

import { Http } from '@angular/http';
import { Headers } from '@angular/http';

import { LoadingController } from 'ionic-angular';
import { TranslateService } from '@ngx-translate/core';
import { ToastController } from 'ionic-angular';

import { SocialSharing } from '@ionic-native/social-sharing';
import { Item } from '../../models/item';

import { Storage } from '@ionic/storage';



//import { ListMasterPage } from '../list-master/list-master';

interface Bauli {
  prossimo: any,
  tipo: any
}

@Component({
  selector: 'page-item-detail',
  templateUrl: 'item-detail.html'
})
export class ItemDetailPage {

  item: any;

  posts: any;
 

  chestjson : any;
  chestsingola : any;
  chestarrayfinale : Bauli[] = [];

  traduttore : TranslateService;
  public isToggled: boolean;
  mystats : string;

  clanlist : any [];
  versione : any;
  build : any;
  forzato : any;

  constructor(public navCtrl: NavController, navParams: NavParams, public items: Items,private platform: Platform, 
    public sanitizer: DomSanitizer, public http: Http,public loadingCtrl: LoadingController,public toastCtrl: ToastController,
    private socialSharing: SocialSharing, public translate: TranslateService,public storage: Storage) {
    

    //default segment
    this.mystats = "mychest";

    this.traduttore=translate;
    this.isToggled = true;

    this.item = navParams.get('item') || items.defaultItem;

    //creo loading che stoppo quando ricevo i dati
     let loading = this.loadingCtrl.create({
          spinner: 'dots',
          content: ``,
          duration: 8000
        });

        loading.onDidDismiss(() => {
       
        });

    loading.present(); 

    //piattaforma

    if(this.platform.is('android')) {
      this.versione="a";
    }else{
      this.versione="i";
    }

    //versione build
    this.build = 39;

    this.forzato="0";
    //se impostato manualmente forzo avvio update (disponibile dopo 6 ore nel client)
    this.storage.get('forzato').then((valore) => {
      
      
    if (valore!=null){
        //console.log("F get : "+valore);
        this.forzato=valore;
    }
   // console.log("F attuale "," - "+this.forzato);

    //prima verifico che sia gia nel mio db. Se si e non si puo aggiornare le stats mostro subito
    this.http.get('http://itzunami.net/chestroyale/view_client.php?checkonly=1&t='+this.item.tag+'&v='+this.build+'&r='+this.versione+'')
    .map(res => res.json()).subscribe
    (data => {
     // console.log(data);
      //vedo output iniziale
      //1 casi stato = 1 gia aggiornato di recente allora mostro subito
      //2 caso = 5 da inserire e/o aggiornare adesso
      this.posts = data;
      //caso 1
      if (this.posts.userinfo.stato == 1 && this.forzato=="0"){
                      this.dispalyInfo(this.posts);
                      //stoppo loading  
                      loading.dismiss();

      }
      //31 = nuovo utente o da aggiornare  3 = aggiorna   (da server)
      //forzato = 1 da locale app
      if (this.posts.userinfo.stato == 3 || this.posts.userinfo.stato == 31 || this.forzato=="1"){

       //nel caso sia stato forzato ripristino default
       if (this.forzato=="1"){
        this.forzato="0";
        this.storage.set('forzato',"0");
       // console.log(" forzato da 1 a 0");
      }
        
        
        //allora faccio update, poi crawling e poi invio e vedo risposta

       //<preference name="OverrideUserAgent" value="Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1" />
        let headersStats = new Headers();
        //user agent modificato da config.xml e qua modifico il xrequested altrimenti non va su android webview
        headersStats.append('X-Requested-With','XMLHttpRequest');
       
        this.http.get('https://statsroyale.com/profile/'+this.item.tag+'/refresh',{headers:headersStats}).map(res1 => res1.text())
        .subscribe(data1 => {
         // console.log("refresh avviato 3 o 31 o forzato da crawling automatico");
         //console.log(data1);
        


        //crawling da client e invio dei dati
        this.http.get('https://statsroyale.com/profile/'+this.item.tag,{headers:headersStats}).map(res => res.text())
        .subscribe(data => {
         // console.log('stats: ', data);
    
    
                //invio al mio sito id e data e risposta con le info
                let headers = new Headers();
                headers.append('Content-Type','application/json');
                let body = {
                tag : this.item.tag,
                htmlraw : data,
                v : this.build,
                r : this.versione,
                forzato : this.forzato
                };
    

                this.http.post('http://itzunami.net/chestroyale/view_client.php',JSON.stringify(body),{ headers : headers})
                  .map(res => res.json())
                  .subscribe(data => {
                  
                  
                   // console.log('da itzunami',data);
                    //parsing data pulita dal mio db
                    this.posts = data;
                    if (this.posts.userinfo.stato == 1){
                       this.dispalyInfo(this.posts);
                    }
                  //stoppo loading  
                  loading.dismiss();
                  });
        });
        
      });//fine refresh

      }//fine if stato 3

    });//fine if tzunami

  });//fine get forzato

    

 
  }

  aggiorna()
  {
  
  
    //serve per richiamare di nuovo update sopra nel caso serva
    this.storage.set('forzato',"1");
    this.forzato="1";
       
    //console.log("FORZATO impostato ", this.forzato);
  
    //chiamo qua altra volta per sicurezza vistro che il sincro fa cagare ed è meglio evitare
    //'X-Requested-With' :'XMLHttpRequest'
    let headersStats = new Headers();
    headersStats.append('X-Requested-With','XMLHttpRequest');
  
    this.http.get('https://statsroyale.com/profile/'+this.item.tag+'/refresh',{headers:headersStats}).map(res => res.text())
    .subscribe(data => {
     // console.log("refresh avviatooooooooo");
     // console.log(data);
  
  
    let mess=this.traduttore.instant("ITEM_DETAILS_UPDATE_DONE");
    let toast = this.toastCtrl.create({
      message: mess,
      duration: 8000,
      position: 'bottom'
    });
    
    toast.present();
    
    this.navCtrl.pop();
  
  
    
  
    });
    
   
    
  }

  dispalyInfo(posts){

    if (posts.userinfo.stato == 1){
      
                 
                  if (posts.clanlist != "0"){
                    this.clanlist = posts.clanlist;
                    this.clanlist.forEach(element => {
                      
                      
                      element.username = this.sistemaHtml(element.username); 
                    
                    });
                  }
      
                  this.chestjson = posts.c.split("|");
      
                  for (let element of this.chestjson) {
      
                  this.chestsingola = element.split(":");
      
                  let baule:Bauli = {prossimo:this.chestsingola[0],tipo:this.chestsingola[1]}; 
                  this.chestarrayfinale.push(baule);
                  }
      
                 
              }



  }

  sistemaHtml(nome){
    let m:String = nome;
    m = m.replace('&lt;','<').replace('&gt;', '>');
    m = m.replace ('&#039;','');
    
    return m;
  }


  //se username non ha spazi e nemmeno il clan ed è lungo X metto ..
  sistemaLunghezza(clan,nome){
    let unoClan:String = clan;
    let dueClan:String = clan;

    let unoNome:String = nome;
    let dueNome:String = nome;

    let unoNoSpazioNome = unoNome.split(' ').join('');   
    
    //se sono qua username è lungo senza spazi
    if (unoNoSpazioNome.length == dueNome.length && dueNome.length>13){

    
    //tolgo spazi
    let unoNoSpazio = unoClan.split(' ').join('');

    //se anche rimuovendo gli spazi è uguale ed è lungo X allora metto ...
    if (unoNoSpazio.length == dueClan.length && dueClan.length>12)
      {
        return this.sistemaHtml(dueClan.substring(0,dueClan.length-4)+"..");
      }
    
      //torna originale
      return this.sistemaHtml(dueClan);
    }
    
    return this.sistemaHtml(dueClan);
 

  }


  livello (numero)
  {
    return 'assets/img/details/'+numero+'.png';

  }

  convertiBaule(baule){

    if (baule==0) return 'assets/img/details/silver.png';
    if (baule==1) return 'assets/img/details/gold.png';
    if (baule==2) return 'assets/img/details/epic.png';
    if (baule==3) return 'assets/img/details/giant.png';
    if (baule==4) return 'assets/img/details/magic.png';
    if (baule==5) return 'assets/img/details/super.png';
    if (baule==6) return 'assets/img/details/legendary.png';

    //caso speciale
    if (baule==7) return 'assets/img/details/silver2.png';

    //errore
    return 'assets/img/details/silver.png';
  }

  convertiNumero(numero){

    if (numero == 0) return "ITEM_DETAILS_NEXTCHEST_PRIMO";
    //per taroccarlo
    if (numero > 40 ) {numero = parseInt(numero) + 7;}
    return ""+numero;

  }

  trovatopos(cerca,t1,t2,t3,t4,t5){
    if (cerca==t1) return 9;
    if (cerca==t2) return 10;
    if (cerca==t3) return 11;
    if (cerca==t4) return 12;
    if (cerca==t5) return 13;
    return 0;
  }

  //mi invento bauli, ma se esiste quello in posizione lo metto
  convertiBauleRandom(posizione){

    //non trovabili come pos, altrimetni aseegno
    var baule9 = 0;  var baule9pos=99999;
    var baule10 = 0; var baule10pos=99999;
    var baule11 = 0; var baule11pos=99999;
    var baule12 = 0; var baule12pos=99999;
    var baule13 = 0; var baule13pos=99999;

    if (this.chestarrayfinale[9]){
     baule9 = this.chestarrayfinale[9].tipo;
     baule9pos = this.chestarrayfinale[9].prossimo;

   // console.log("nove esiste assegnato");
    }

    if (this.chestarrayfinale[10]){
     baule10 = this.chestarrayfinale[10].tipo;
     baule10pos = this.chestarrayfinale[10].prossimo;
     }
    if (this.chestarrayfinale[11]){
     baule11 = this.chestarrayfinale[11].tipo;
     baule11pos = this.chestarrayfinale[11].prossimo;
    }
    if (this.chestarrayfinale[12]){
     baule12 = this.chestarrayfinale[12].tipo;
     baule12pos = this.chestarrayfinale[12].prossimo;
    }
    if (this.chestarrayfinale[13]){
     baule13 = this.chestarrayfinale[13].tipo;
     baule13pos = this.chestarrayfinale[13].prossimo;
    }

    let q = this.trovatopos(posizione,baule9pos,baule10pos,baule11pos,baule12pos,baule13pos);
   // console.log("q "+q+" con posizione "+posizione+ " baule9pos "+ baule9pos);

    //caso prima posizione
    for (let i=9;i<13;i++){
    if (posizione==i){
     // console.log("posizione "+posizione);
        if (q>0){
          if (q==9) { return this.convertiBaule(baule9); }
          if (q==10){ return this.convertiBaule(baule10);}
          if (q==11){ return this.convertiBaule(baule11);}
          if (q==12){ return this.convertiBaule(baule12);}
        }else{
       //non trovato metto silver o oro

       //per stabilire un random variabile fisso per utente prendo ultima pos ultimo baule
       //se pari ssos , se dispari soss
       
        if (baule9pos%2==0){

          if (posizione==9)  { return this.convertiBaule(0);}
          if (posizione==10) { return this.convertiBaule(0);}
          if (posizione==11) { return this.convertiBaule(1);}
          if (posizione==12) { return this.convertiBaule(7);}

        }else{

          if (posizione==9)  { return this.convertiBaule(0);}
          if (posizione==10) { return this.convertiBaule(1);}
          if (posizione==11) { return this.convertiBaule(0);}
          if (posizione==12) { return this.convertiBaule(7);}

        }

        //caso errore
        return this.convertiBaule(0); //silver
        }

    }
    }
 
 


  }

  updateF(){

    
   // console.log("Toggled: "+ this.isToggled); 
    //this.traduttore.instant("ITEM_CREATE_ADDED"),
    let mess="";
    if (this.isToggled==false){
      mess=this.traduttore.instant("ITEM_DETAILS_OFF");
    }
    else{
      mess=this.traduttore.instant("ITEM_DETAILS_ON");
    }
      let toast = this.toastCtrl.create({
      message: mess,
      duration: 3000,
      position: 'bottom'
    });
    toast.present();
   

  }


  share(tag){
    let url="http://itzunami.net/chestroyale/share/?t=";
     this.socialSharing.share(this.translate.instant("SHARE_NOME")+" "+ this.translate.instant("SHARE_ALL"), null, "www/assets/img/share/icona.png", url+tag)
  }

  deleteItem(item) {

    this.items.delete(item);
    this.navCtrl.pop();
  }

 livellotab1(numero){
    return 'assets/img/details/'+numero+'.png';
  }

 

  openItemClan(tag){

    
    let questo =  
    {
        "nome": tag,
        "tag": tag
     };
    

    let mostra = new Item (questo) ;

     this.navCtrl.push(ItemDetailPage, {
      item: mostra
    });
  }




mostraUrl(url){
  
      window.open(url,'_blank'); 
     
  
    }



}
