import { ListMasterPage } from './list-master/list-master';


import { TabsPage } from './tabs/tabs';
import { TutorialPage } from './tutorial/tutorial';
import { MyInizioPage } from './my-inizio/my-inizio';
import { SearchPage } from './search/search';


import { Tab1Page } from './tab1/tab1';
import { Tab2Page } from './tab2/tab2';
import { Tab3Page } from './tab3/tab3';

//da rimuovere
//import { TopPlayersPage } from './top-players/top-players';
//import { PopularCardsPage } from './popular-cards/popular-cards';
//import { PopularDecksPage } from './popular-decks/popular-decks';




// The page the user lands on after opening the app and without a session
export const FirstRunPage = MyInizioPage;

// The main page the user will see as they use the app over a long period of time.
// Change this if not using tabs
export const MainPage = TabsPage;

// The initial root pages for our tabs (remove if not using tabs)
export const Tab1Root = ListMasterPage;
export const Tab2Root = SearchPage;
export const Tab3Root = Tab1Page;
export const Tab4Root = Tab2Page;
export const Tab5Root = Tab3Page;

