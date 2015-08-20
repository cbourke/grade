<?php

class Egg {

  private static $easterEggs = array(
"",
"You're the man now dog.",
"There are only two important days in your life: the day you are born, and the day you find out why.",
"There are three things all wise men fear: the sea in storm, a night with no moon, and the anger of a gentle man.",
"Beware the Fury of a Patient Man",
"At Least I Have Chicken",
"Happy Life Day!",
"cash rules everything around me cream get the money, dolla dolla bills yall",
"That is not dead which can eternal lie, And with strange aeons even death may die.",
"Here comes a special boy!",
"Three Days, Three Acres, Three Thousand Men",
"That gum you like is going to come back in style",
"Through the darkness of future past, the magician longs to see, one chance out between two worlds, fire walk with me!",
"It is by will alone I set my mind in motion. It is by the juice of sapho that thoughts acquire speed, the lips acquire stains, the stains become a warning. It is by will alone I set my mind in motion.",
"YOU suck!  Dallas Rules!",
"How Is Babby Formed?",
"Grow old along with me!
The best is yet to be,
The last of life, for which the first was made",
"Evil!  Pure and simple from the 8-th dimension!",
"Oh, InDEED.",
"Now I am become Death, the destroyer of worlds",
"My name is Ozymandias, king of kings: Look on my works, ye Mighty, and despair!",
"What is best in life? To crush your enemies, to see them driven before you, and to hear the lamentations of their women.",
"So shines a good deed in a weary world.",
"Up the airy mountains; Down the rushy Glen, We dare not go a-hunting; For fear of little men;",
"I watched c-beams glitter in the dark near the Tanhauser Gate.",
"One of paper = Four of Coin",
"Everybody knows that the war is over, everybody knows that the good guys lost.",
"Everything is Terrible!",
"Swine merchant, your time is near at hand... Your presence here affects the mind of my people like a fever.  You, yakoo, are the bearer of 9,999 disseases: evil, corrupt pork-chop-eating atrocities!",
"It is a civilization which has destroyed the simplicity and repose of life; replaced its contentment, its poetry, its soft romance-dreams and visions with the money-fever, sordid ideals, vulgar ambitions, and the sleep which does not refresh; it has invented a thousand useless luxuries, and turned them into necessities; it has created a thousand vicious appetites and satisfies none of them; it has dethroned God and set up a shekel in His place.",
"Be Aggressive, B-E-Agressive!",
"Pretty much everywhere, its gonna be hot",
"Never put salt in your eyes",
"I'm going to ruin your bobbum with my equipmunk!!",
"Dandy Hobo",
"You're an errand boy, sent by grocery clerks, to collect a bill.",
"Shut up, Wesley!",
"Woe to those who call good evil and evil good.",
"You can always tell a Milford man",
"Garbage Day!",
"RIP ODB",
"The arc of the moral universe is long but it bends toward justice",
"I must not fear. Fear is the mind-killer. Fear is the little-death that brings total obliteration...",
"For your Health!",
"Puppets aren't real, but they can come to life!",
"For he is the Kwisatz Haderach!",
"Carlton Dance!",
"I have come here to chew bubble gum and kick ass.  And I'm all out of bubblegum.",
"You're tearing me apart, Lisa!",
"My pen!",
"It is said that what is called the Spirit of an Age is something to which one cannot return. That this spirit gradually dissipates is due to the world's coming to an end.",
"For the Angel of Death spread his wings on the blast, And breathed in the face of the foe as he passed: And the eyes of the sleepers waxed deadly and chill, And their hearts but once heaved, and for ever grew still!"
);

public static function getEgg() {
  $rand_key = array_rand(self::$easterEggs, 1);
  return self::$easterEggs[$rand_key];
}

}

//print Egg::getEgg() . "\n";
?>