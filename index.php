<?php
  function get_easily_pronounced_random_phrase($length = 5) {      // shuffling a large array is always result with better letter sequences.
    //-----------------------------------------------------------------------------------------------------------------
    //baseline a-z
    $seed = 'abcdefghijklmnopqrstuvwxyz';
    $seed = str_repeat($seed, 2);  //duplicate
    $seed = str_split($seed);
    sort($seed);
    shuffle($seed);

    //-----------------------------------------------------------------------------------------------------------------
    //interlace more vowels
    $vowels = ['', '', '', '', '', '', //add nothing (on large scale its 40% not adding)
               'a', 'e', 'i', 'o', 'u', 'y', 'ee', 'oa', 'oo']; //add ok vowels (on large scale its 60% adding)
    $vowels = array_merge($vowels, $vowels, $vowels, $vowels, $vowels, $vowels, $vowels, $vowels, $vowels, $vowels);
    shuffle($vowels);

    //make word better pronounced by adding vowels.
    $seed = array_map(function ($a, $b) {
      return $a . $b;
    }, $seed, $vowels);

    //-----------------------------------------------------------------------------------------------------------------
    //replace 24 bad combinations
    $not_in_start = ['/ck/', '/cm/', '/dr/', '/ds/',
                     '/ft/', '/gh/', '/gn/', '/kr/',
                     '/ks/', '/ls/', '/lt/', '/lr/',
                     '/mp/', '/mt/', '/ms/', '/ng/',
                     '/ns/', '/rd/', '/rg/', '/rs/',
                     '/rt/', '/ss/', '/ts/', '/tch/'];

    $okish = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', //replacements: delete (on large scale its 26.9% of deleting the bad combination)
              'a', 'e', 'i', 'o', 'u', 'y', 'b', 'c', 'd', 'f', 'g', 'h', //replacements: single letter (on large scale its 73% of replacing the bad combination with 1-or-2 letter alternative).
              'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'z',
              'pt', 'gl', 'gr', 'ch', 'ph', 'ee', 'oa', 'oo', 'ps', 'sh', 'st', 'th', 'wh']; //replacements: allowed pairs
    $okish = array_merge($okish, $okish, $okish, $okish, $okish, $okish);
    shuffle($okish);

    $seed = str_split(preg_replace($not_in_start, $okish, implode($seed)));

    //-----------------------------------------------------------------------------------------------------------------
    //replace duplicated pairs
    $seed = str_split(preg_replace(['/aa/', '/bb/', '/cc/', '/dd/', '/ee/', '/ff/', '/gg/', '/hh/', '/ii/', '/jj/',
                                    '/kk/', '/ll/', '/mm/', '/nn/', '/pp/', '/qq/', '/rr/', '/ss/', '/tt/', '/uu/',
                                    '/vv/', '/ww/', '/xx/', '/yy/', '/zz/'], ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''], implode($seed))
    );

    //-----------------------------------------------------------------------------------------------------------------
    //cut proper length
    $seed = implode($seed);
    $length = min(max(3, $length), mb_strlen($seed)); //make sure requested word is not too small or too big.

    return mb_substr($seed, 0, $length);
  }

  echo get_random_word(10);
