import { Component, ViewChild } from '@angular/core';
import { Validators, FormBuilder, FormGroup } from '@angular/forms';
import { NavController, ViewController } from 'ionic-angular';


import { Item } from '../../models/item';
import { TranslateService } from '@ngx-translate/core';
import { ToastController } from 'ionic-angular';


@Component({
  selector: 'page-item-create',
  templateUrl: 'item-create.html'
})
export class ItemCreatePage {
  @ViewChild('fileInput') fileInput;

  isReadyToSave: boolean;

  item: any;

  form: FormGroup;

  avatarItems: Item[] = [];

  traduttore : TranslateService;

  constructor(public navCtrl: NavController, public viewCtrl: ViewController, formBuilder: FormBuilder,translate: TranslateService,public toastCtrl: ToastController) {

    this.traduttore=translate;

    this.form = formBuilder.group({
      profilePic: ['',Validators.required],
      name: ['', Validators.required],
      tag: ['',Validators.required],
      youtube: false
    });

    // Watch the form for changes, and
    this.form.valueChanges.subscribe((v) => {
      this.isReadyToSave = this.form.valid;
    });

  //elenco avatar
  let avatarItems = [
  
      {
        "nome": translate.instant("AVATAR_A"),
        "profilePic": "assets/img/avatar/ArchersCard.jpg"
      },
      {
        "nome": translate.instant("AVATAR_B"),
        "profilePic": "assets/img/avatar/BabyDragonCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_B1"),
        "profilePic": "assets/img/avatar/BanditCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_B2"),
        "profilePic": "assets/img/avatar/BarbariansCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_B3"),
        "profilePic": "assets/img/avatar/BomberCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_B4"),
        "profilePic": "assets/img/avatar/BowlerCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_D"),
        "profilePic": "assets/img/avatar/DarkPrinceCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_E"),
        "profilePic": "assets/img/avatar/ElectroWizardCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_E1"),
        "profilePic": "assets/img/avatar/ExecutionerCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_G"),
        "profilePic": "assets/img/avatar/GiantCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_G1"),
        "profilePic": "assets/img/avatar/GiantSkeletonCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_G2"),
        "profilePic": "assets/img/avatar/GoblinsCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_G3"),
        "profilePic": "assets/img/avatar/GolemCard.jpg"
      },
           {
        "nome": translate.instant("AVATAR_I"),
        "profilePic": "assets/img/avatar/IceWizardCard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_M"),
        "profilePic": "assets/img/avatar/MegaMinionCard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_M1"),
        "profilePic": "assets/img/avatar/MinerCard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_M2"),
        "profilePic": "assets/img/avatar/MiniPEKKACard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_M3"),
        "profilePic": "assets/img/avatar/MusketeerCard.jpg"
      },
        {
        "nome": translate.instant("AVATAR_M4"),
        "profilePic": "assets/img/avatar/MegaKnightCard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_N"),
        "profilePic": "assets/img/avatar/NightWitchCard.png"
      },
       {
        "nome": translate.instant("AVATAR_P"),
        "profilePic": "assets/img/avatar/PEKKACard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_P2"),
        "profilePic": "assets/img/avatar/PrinceCard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_P3"),
        "profilePic": "assets/img/avatar/PrincessCard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_R"),
        "profilePic": "assets/img/avatar/RoyalGiantCard.jpg"
      },
       {
        "nome": translate.instant("AVATAR_T"),
        "profilePic": "assets/img/avatar/ThreeMusketeersCard.jpg"
      },
        {
        "nome": translate.instant("AVATAR_W"),
        "profilePic": "assets/img/avatar/WitchCard.jpg"
      }
    ];

  

  for (let item of avatarItems) {
      this.avatarItems.push(new Item(item));
    }

  }

  ionViewDidLoad() {

  }





  getProfileImageStyle() {
    return 'url(' + this.form.controls['profilePic'].value + ')'
  }

  /**
   * The user cancelled, so we dismiss without sending data back.
   */
  cancel() {
    this.viewCtrl.dismiss();
  }

  /**
   * The user is done and wants to create the item, so return it
   * back to the presenter.
   */
  done() {
    if (!this.form.valid) { return; }
    this.presentToast();
    this.viewCtrl.dismiss(this.form.value);
    
    
  }

  presentToast() {
    let toast = this.toastCtrl.create({
      message: this.traduttore.instant("ITEM_CREATE_ADDED"),
      duration: 3000,
      position: 'bottom'
    });
    toast.present();
  }

  
}
