import { Component, ViewChild } from '@angular/core';
import { Platform, Nav, Config } from 'ionic-angular';

import { StatusBar } from '@ionic-native/status-bar';
import { SplashScreen } from '@ionic-native/splash-screen';

//import { ContentPage } from '../pages/content/content';
import { FirstRunPage } from '../pages/pages';
import { ListMasterPage } from '../pages/list-master/list-master';

import { SearchPage } from '../pages/search/search';


import { TabsPage } from '../pages/tabs/tabs';
import { TutorialPage } from '../pages/tutorial/tutorial';
import { MyInizioPage } from '../pages/my-inizio/my-inizio';

//import { TopPlayersPage } from '../pages/top-players/top-players';
//import { PopularCardsPage } from '../pages/popular-cards/popular-cards';
//import { PopularDecksPage } from '../pages/popular-decks/popular-decks';



import { TranslateService } from '@ngx-translate/core';



@Component({
  //qua ho rimosso il <menu
  template: `


  <ion-nav #content [root]="rootPage"></ion-nav>`
})
export class MyApp {
  rootPage = FirstRunPage;

  @ViewChild(Nav) nav: Nav;

//menu laterale che non serve
  pages: any[] = [
    { title: 'Tutorial', component: TutorialPage },
    { title: 'Tabs', component: TabsPage },
    //{ title: 'Content', component: ContentPage },
    { title: 'Master Detail', component: ListMasterPage },
      { title: 'Search', component: SearchPage }
  ]

  constructor(private translate: TranslateService, private platform: Platform, private config: Config, private statusBar: StatusBar, private splashScreen: SplashScreen) {
    this.initTranslate();
  }

  ionViewDidLoad() {
    this.platform.ready().then(() => {
      // Okay, so the platform is ready and our plugins are available.
      // Here you can do any higher level native things you might need.
      this.statusBar.styleDefault();
      this.splashScreen.hide();
    });
  }

  initTranslate() {
    // Set the default language for translation strings, and the current language.
    this.translate.setDefaultLang('en');

    if (this.translate.getBrowserLang() !== undefined) {
    //  console.log("AAAAAAAVVVVVVVVVV"+this.translate.getBrowserLang());

      //RIMETTERE QUESTO PER FARLO ANDARE OKKKKKKKKKKKKKKKKKKKKKKKKKK
      this.translate.use(this.translate.getBrowserLang());
     // this.translate.use('en');
       //RIMETTERE QUESTO PER FARLO ANDARE OKKKKKKKKKKKKKKKKKKKKKKKKKK
    } else {
      this.translate.use('en'); // Set your language here
    }

    this.translate.get(['BACK_BUTTON_TEXT']).subscribe(values => {
      this.config.set('ios', 'backButtonText', values.BACK_BUTTON_TEXT);
    });
  }

  openPage(page) {
    // Reset the content nav to have just this page
    // we wouldn't want the back button to show in this scenario
    this.nav.setRoot(page.component);
  }
}
