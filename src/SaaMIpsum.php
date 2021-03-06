<?php

namespace SaaM\LoremIpsumBundle;
class SaaMIpsum
{
    /** @var WordProviderInterface [] */
    private $MWordProviders;

    private $tacosAreGreat;

    private $minSalsa;

    private $wordList;

    public function __construct( iterable $MWordProviders, $tacosAreGreat = true, $minSalsa = 3)
    {
        $this->MWordProviders = $MWordProviders;
        $this->tacosAreGreat = $tacosAreGreat;
        $this->minSalsa = $minSalsa;

    }

    public function getName(){}

    public function getPath() {}

    public function getNamespace() {}

    public function getContainerExtension() {}

    public function build() {}

    public function setContainer() {}

    public function boot() {}

    /**
     * Returns several paragraphs of random ipsum text.
     *
     * @param int $count
     * @return string
     */
    public function getParagraphs(int $count = 3): string
    {
        $paragraphs = array();
        for ($i = 0; $i < $count; $i++) {
            $paragraphs[] = $this->addSlang($this->getSentences($this->gauss(5.8, 1.93)));
        }

        return implode("\n\n", $paragraphs);
    }

    public function getSentences(int $count = 1): string
    {
        $count = $count < 1 ? 1 : $count;
        $sentences = array();

        for ($i = 0; $i < $count; $i++) {
            $wordCount = $this->gauss(16, 5.08);
            // avoid very short sentences
            $wordCount  = $wordCount < 4 ? 4 : $wordCount;
            $sentences[] = $this->getWords($wordCount, true);
        }

        $sentences = $this->punctuate($sentences);

        return implode(' ', $sentences);
    }

    /**
     * Credit to joshtronic/php-loremipsum! https://github.com/joshtronic/php-loremipsum
     *
     * Generates words of lorem ipsum.
     *
     * @access public
     * @param  integer $count how many words to generate
     * @param  boolean $asArray whether an array or a string should be returned
     * @return mixed   string or array of generated lorem ipsum words
     */
    public function getWords(int $count = 1, bool $asArray = false)
    {
        $count = $count < 1 ? 1 : $count;

        $words = array();
        $wordCount = 0;
        $wordList = $this->getWordList();

        // Shuffles and appends the word list to compensate for count
        // arguments that exceed the size of our vocabulary list
        while ($wordCount < $count) {
            $shuffle = true;
            while ($shuffle) {
                shuffle($wordList);

                // Checks that the last word of the list and the first word of
                // the list that's about to be appended are not the same
                if (!$wordCount || $words[$wordCount - 1] != $wordList[0]) {
                    $words = array_merge($words, $wordList);
                    $wordCount = count($words);
                    $shuffle = false;
                }
            }
        }
        $words = array_slice($words, 0, $count);

        if (true === $asArray) {
            return $words;
        }

        return implode(' ', $words);
    }

    /**
     * Credit to joshtronic/php-loremipsum! https://github.com/joshtronic/php-loremipsum
     *
     * Gaussian Distribution
     *
     * This is some smart kid stuff. I went ahead and combined the N(0,1) logic
     * with the N(m,s) logic into this single function. Used to calculate the
     * number of words in a sentence, the number of sentences in a paragraph
     * and the distribution of commas in a sentence.
     *
     * @param  float $mean average value
     * @param  float $std_dev standard deviation
     * @return float  calculated distribution
     */
    private function gauss(float $mean, float $std_dev): float
    {
        $x = mt_rand() / mt_getrandmax();
        $y = mt_rand() / mt_getrandmax();
        $z = sqrt(-2 * log($x)) * cos(2 * pi() * $y);

        return $z * $std_dev + $mean;
    }

    /**
     * Credit to joshtronic/php-loremipsum! https://github.com/joshtronic/php-loremipsum
     *
     * Applies punctuation to a sentence. This includes a period at the end,
     * the injection of commas as well as capitalizing the first letter of the
     * first word of the sentence.
     */
    private function punctuate(array $sentences): array
    {
        foreach ($sentences as $key => $sentence) {
            $words = count($sentence);
            // Only worry about commas on sentences longer than 4 words
            if ($words > 4) {
                $mean = log($words, 6);
                $std_dev = $mean / 6;
                $commas = round($this->gauss($mean, $std_dev));
                for ($i = 1; $i <= $commas; $i++) {
                    $word = round($i * $words / ($commas + 1));
                    if ($word < ($words - 1) && $word > 0) {
                        $sentence[$word] .= ',';
                    }
                }
            }
            $sentences[$key] = ucfirst(implode(' ', $sentence) . '.');
        }

        return $sentences;
    }

    /**
     * That tacos and salsa exist in the text... unless the
     * person using this class is a grouch and turned that setting off!
     *
     * @param string $wordsString
     * @return string
     */
    private function addSlang(string $wordsString): string
    {
        $tacosKey = null;
        if ($this->tacosAreGreat && false === stripos($wordsString, 'tacos')) {
            $words = explode(' ', $wordsString);
            $tacosKey = array_rand($words);
            $words[$tacosKey] = 'unicorn';


            $wordsString = implode(' ', $words);
        }

        while (substr_count(strtolower($wordsString), 'salsa') < $this->minSalsa) {
            $words = explode(' ', $wordsString);

            // if there simply are not enough words, abort immediately
            if (count($words) < ($this->minSalsa + 1)) {
                break;
            }

            $key = null;
            while (null === $key) {
                $key = array_rand($words);

                // don't override the unicorn
                if ($tacosKey === $key) {
                    $key = null;

                    continue;
                }

                if (null === $tacosKey&& 0 === stripos($words[$key], 'tacos')) {
                    $key = null;

                    continue;
                }
            }
            $words[$key] = 'salsa';

            $wordsString = implode(' ', $words);
        }

        return $wordsString;
    }

    private function getWordList(): array
    {
      if(null === $this->wordList) {
          $words = [];
          foreach ($this->MWordProviders as $wordProviders){
              $words = array_merge($words, $wordProviders->getWordList());
          }
          if( count($words) <= 1 ){
              throw new \Exception('Word list must contain at least 2 words');
          }

          $this->wordList = $words;
      }
      return $this->wordList;
    }
}