<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Service
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util;

use Sfynx\ToolBundle\Builder\PiRandomBuilderInterface;

/**
 * Description of the random manager
 *
 * <code>
 *     $random    = $this-container->get('sfynx.tool.random_manager');
 * </code>
 * 
 * @category   Tool
 * @package    Util
 * @subpackage Service
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class PiRandomManager implements PiRandomBuilderInterface 
{   
    /** gaussianWeightedRandom() */
    const RANDOM_Gaussian = 'gaussian';
    
    /** bellWeightedRandom() */
    const RANDOM_Bell = 'bell';   
    
    /** gaussianWeightedRisingRandom() */
    const RANDOM_GaussianRising = 'gaussianRising';   
    
    /** gaussianWeightedFallingRandom() */
    const RANDOM_GaussianFalling = 'gaussianFalling';  
    
    /** gammaWeightedRandom() */
    const RANDOM_Gamma = 'gamma'; 
    
    /** QaDgammaWeightedRandom() */
    const RANDOM_GammaQaD = 'gammaQaD';  
    
    /** logarithmic10WeightedRandom() */
    const RANDOM_Logarithmic10 = 'log10';  
    
    /** logarithmicWeightedRandom() */
    const RANDOM_Logarithmic = 'log'; 
    
    /** poissonWeightedRandom() */
    const RANDOM_Poisson = 'poisson';  
    
    /** domeWeightedRandom() */
    const RANDOM_Dome = 'dome';  
    
    /** sawWeightedRandom() */
    const RANDOM_Saw = 'saw';  
    
    /** pyramidWeightedRandom() */
    const RANDOM_Pyramid = 'pyramid';  
    
    /** linearWeightedRandom() */
    const RANDOM_Linear = 'linear';  
    
    /** nonWeightedRandom() */
    const RANDOM_Unweighted = 'non';                   
    
    /**
     * returns all supported random types
     *
     * @return array
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function getSupportedRandomTypes()
    {
        return array(
            self::RANDOM_Gaussian,
            self::RANDOM_Bell,
            self::RANDOM_GaussianRising,
            self::RANDOM_GaussianFalling,
            self::RANDOM_Gamma,
            self::RANDOM_GammaQaD,
            self::RANDOM_Logarithmic10,
            self::RANDOM_Logarithmic,
            self::RANDOM_Poisson,
            self::RANDOM_Dome,
            self::RANDOM_Saw,
            self::RANDOM_Pyramid,
            self::RANDOM_Linear,
            self::RANDOM_Unweighted
        );
    }
    
    /**
     * get random value
     *
     * @param string $Method
     * @param string $LowValue
     * @param string $maxRand
     * 
     * @return string the random value
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function weightedRandom($Method, $LowValue, $maxRand)
    {
        switch (strtolower($Method)) {
            case strtolower(self::RANDOM_Gaussian) :
                $rVal = static::gaussianWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Bell) :
                $rVal = static::bellWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_GaussianRising) :
                $rVal = static::gaussianWeightedRisingRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_GaussianFalling) :
                $rVal = static::gaussianWeightedFallingRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Gamma) :
                $rVal = static::gammaWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_GammaQaD) :
                $rVal = static::QaDgammaWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Logarithmic10) :
                $rVal = static::logarithmic10WeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Logarithmic) :
                $rVal = static::logarithmicWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Poisson) :
                $rVal = static::poissonWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Dome) :
                $rVal = static::domeWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Saw) :
                $rVal = static::sawWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Pyramid) :
                $rVal = static::pyramidWeightedRandom($LowValue, $maxRand);
                break ;
                
            case strtolower(self::RANDOM_Linear) :
                $rVal = static::linearWeightedRandom($LowValue, $maxRand);
                break ;
                
            default :
                $rVal = static::nonWeightedRandom($LowValue, $maxRand);
                break ;
        }
        
        if (!$rVal 
                || !in_array(strtolower($Method), static::getSupportedRandomTypes())
        ) {
            throw new \Exception("$Method is not supported by your php version");
        }
        
        return $rVal;
    }    
    


    public function mkseed()
    {
        srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff) ;
    }


    /*
    public function factorial($in) {
        if ($in == 1) {
            return $in ;
        }
        return ($in * factorial($in - 1.0)) ;
    }   //  function factorial()


    public function factorial($in) {
        $out = 1 ;
        for ($i = 2; $i <= $in; $i++) {
            $out *= $i ;
        }

        return $out ;
    }   //  function factorial()
    */


    public function random_0_1()
    {
        //  returns random number using mt_rand() with a flat distribution from 0 to 1 inclusive
        //
        return (float) mt_rand() / (float) mt_getrandmax() ;
    }


    public function random_PN()
    {
        //  returns random number using mt_rand() with a flat distribution from -1 to 1 inclusive
        //
        return (2.0 * static::random_0_1()) - 1.0 ;
    } 
    
    public function gauss()
    {
        static $useExists = false ;
        static $useValue ;

        if ($useExists) {
            //  Use value from a previous call to this function
            //
            $useExists = false ;
            return $useValue ;
        } else {
            //  Polar form of the Box-Muller transformation
            //
            $w = 2.0 ;
            while (($w >= 1.0) || ($w == 0.0)) {
                $x = static::random_PN() ;
                $y = static::random_PN() ;
                $w = ($x * $x) + ($y * $y) ;
            }
            $w = sqrt((-2.0 * log($w)) / $w) ;

            //  Set value for next call to this function
            //
            $useValue = $y * $w ;
            $useExists = true ;

            return $x * $w ;
        }
    }


    public function gauss_ms($mean, $stddev)
    {
        //  Adjust our gaussian random to fit the mean and standard deviation
        //  The division by 4 is an arbitrary value to help fit the distribution
        //      within our required range, and gives a best fit for $stddev = 1.0
        //
        return static::gauss() * ($stddev/4) + $mean;
    }


    public static function gaussianWeightedRandom($LowValue, $maxRand, $mean = 0.0, $stddev = 2.0)
    {
        //  Adjust a gaussian random value to fit within our specified range
        //      by 'trimming' the extreme values as the distribution curve
        //      approaches +/- infinity
        $rand_val = $LowValue + $maxRand ;
        while (($rand_val < $LowValue) || ($rand_val >= ($LowValue + $maxRand))) {
            $rand_val = floor(gauss_ms($mean,$stddev) * $maxRand) + $LowValue ;
            $rand_val = ($rand_val + $maxRand) / 2 ;
        }

        return $rand_val ;
    }


    public static function bellWeightedRandom($LowValue, $maxRand)
    {
        return static::gaussianWeightedRandom( $LowValue, $maxRand, 0.0, 1.0) ;
    }

    public static function gaussianWeightedRisingRandom($LowValue, $maxRand)
    {
        //  Adjust a gaussian random value to fit within our specified range
        //      by 'trimming' the extreme values as the distribution curve
        //      approaches +/- infinity
        //  The division by 4 is an arbitrary value to help fit the distribution
        //      within our required range
        $rand_val = $LowValue + $maxRand ;
        while (($rand_val < $LowValue) || ($rand_val >= ($LowValue + $maxRand))) {
            $rand_val = $maxRand - round((abs(static::gauss()) / 4) * $maxRand) + $LowValue ;
        }

        return $rand_val ;
    }


    public static function gaussianWeightedFallingRandom($LowValue, $maxRand)
    {
        //  Adjust a gaussian random value to fit within our specified range
        //      by 'trimming' the extreme values as the distribution curve
        //      approaches +/- infinity
        //  The division by 4 is an arbitrary value to help fit the distribution
        //      within our required range
        $rand_val = $LowValue + $maxRand ;
        while (($rand_val < $LowValue) || ($rand_val >= ($LowValue + $maxRand))) {
            $rand_val = floor((abs(static::gauss()) / 4) * $maxRand) + $LowValue ;
        }

        return $rand_val ;
    }


    public static function logarithmic($mean=1.0, $lambda = 5.0)
    {
        return ($mean * -log(static::random_0_1())) / $lambda;
    }


    public static function logarithmicWeightedRandom($LowValue, $maxRand)
    {
        do {
            $rand_val = static::logarithmic();
        } while ($rand_val > 1) ;

        return floor($rand_val * $maxRand) + $LowValue;
    }


    public static function logarithmic10($lambda = 0.5)
    {
        return abs(-log10(static::random_0_1()) / $lambda);
    }


    public static function logarithmic10WeightedRandom($LowValue, $maxRand)
    {
        do {
            $rand_val = static::logarithmic10();
        } while ($rand_val > 1) ;

        return floor($rand_val * $maxRand) + $LowValue;
    } 


    public static function gamma($lambda = 3.0)
    {
        $wLambda = $lambda + 1.0;
        if ($lambda <= 8.0) {
            //  Use direct method, adding waiting times
            $x = 1.0 ;
            for ($j = 1; $j <= $wLambda; $j++) {
                $x *= static::random_0_1() ;
            }
            $x = -log($x) ;
        } else {
            //  Use rejection method
            do {
                do {
                    //  Generate the tangent of a random angle, the equivalent of
                    //      $y = tan(pi * random_0_1())
                    do {
                        $v1 = static::random_0_1();
                        $v2 = static::random_PN();
                    } while (($v1 * $v1 + $v2 * $v2) > 1.0) ;
                    $y = $v2 / $v1 ;
                    $s = sqrt(2.0 * $lambda + 1.0) ;
                    $x = $s * $y + $lambda ;
                //  Reject in the region of zero probability
                } while ($x <= 0.0) ;
                //  Ratio of probability function to comparison function
                $e = (1.0 + $y * $y) * exp($lambda * log($x / $lambda) - $s * $y) ;
            //  Reject on the basis of a second uniform deviate
            } while (static::random_0_1() > $e);
        }

        return $x;
    }


    public static function gammaWeightedRandom($LowValue, $maxRand)
    {
        do {
            $rand_val = static::gamma() / 12 ;
        } while ($rand_val > 1) ;

        return floor($rand_val * $maxRand) + $LowValue;
    }


    public static function QaDgammaWeightedRandom($LowValue, $maxRand)
    {
        return round((asin(static::random_0_1()) + (asin(static::random_0_1()))) * $maxRand / pi()) + $LowValue ;
    }


    public static function gammaln($in)
    {
        $tmp  = $in + 4.5 ;
        $tmp -= ($in - 0.5) * log($tmp) ;

        $ser = 1.000000000190015
                + (76.18009172947146 / $in)
                - (86.50532032941677 / ($in + 1.0))
                + (24.01409824083091 / ($in + 2.0))
                - (1.231739572450155 / ($in + 3.0))
                + (0.1208650973866179e-2 / ($in + 4.0))
                - (0.5395239384953e-5 / ($in + 5.0)) ;

        return (log(2.5066282746310005 * $ser) - $tmp) ;
    }


    public static function poisson($lambda = 1.0)
    {
        static $oldLambda ;
        static $g, $sq, $alxm ;

        if ($lambda <= 12.0) {
            //  Use direct method
            if ($lambda <> $oldLambda) {
                $oldLambda = $lambda ;
                $g = exp(-$lambda) ;
            }
            $x = -1 ;
            $t = 1.0 ;
            do {
                ++$x ;
                $t *= static::random_0_1() ;
            } while ($t > $g) ;
        } else {
            //  Use rejection method
            if ($lambda <> $oldLambda) {
                $oldLambda = $lambda ;
                $sq = sqrt(2.0 * $lambda) ;
                $alxm = log($lambda) ;
                $g = $lambda * $alxm - static::gammaln($lambda + 1.0) ;
            }
            do {
                do {
                    //  $y is a deviate from a Lorentzian comparison function
                    $y = tan(pi() * static::random_0_1()) ;
                    $x = $sq * $y + $lambda ;
                //  Reject if close to zero probability
                } while ($x < 0.0) ;
                $x = floor($x) ;
                //  Ratio of the desired distribution to the comparison function
                //  We accept or reject by comparing it to another uniform deviate
                //  The factor 0.9 is used so that $t never exceeds 1
                $t = 0.9 * (1.0 + $y * $y) * exp($x * $alxm - static::gammaln($x + 1.0) - $g) ;
            } while (static::random_0_1() > $t) ;
        }

        return $x;
    }


    public static function poissonWeightedRandom($LowValue, $maxRand)
    {
        do {
            $rand_val = static::poisson() / $maxRand ;
        } while ($rand_val > 1) ;

        return floor($x * $maxRand) + $LowValue ;
    }


    public static function binomial($lambda=6.0)
    {
    }


    public static function domeWeightedRandom($LowValue, $maxRand)
    {
        return floor(sin(static::random_0_1() * (pi() / 2)) * $maxRand) + $LowValue ;
    }


    public static function sawWeightedRandom($LowValue, $maxRand)
    {
        return floor((atan(static::random_0_1()) + atan(static::random_0_1())) * $maxRand / (pi()/2)) + $LowValue ;
    }


    public static function pyramidWeightedRandom($LowValue, $maxRand)
    {
        return floor((static::random_0_1() + static::random_0_1()) / 2 * $maxRand) + $LowValue ;
    }


    public static function linearWeightedRandom($LowValue, $maxRand)
    {
        return floor(static::random_0_1() * ($maxRand)) + $LowValue ;
    }


    public static function nonWeightedRandom($LowValue, $maxRand)
    {
        return rand($LowValue,$maxRand+$LowValue-1) ;
    }
}
