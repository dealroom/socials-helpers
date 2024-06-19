<?php

declare(strict_types=1);

namespace Tests\Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Normalizers\AppleMusicNormalizer;
use Dealroom\SocialsHelpers\Normalizers\FacebookPageNormalizer;
use Dealroom\SocialsHelpers\Normalizers\FacebookProfileNormalizer;
use Dealroom\SocialsHelpers\Normalizers\Factory;
use Dealroom\SocialsHelpers\Normalizers\InstagramNormalizer;
use Dealroom\SocialsHelpers\Normalizers\LinkedinCompanyNormalizer;
use Dealroom\SocialsHelpers\Normalizers\LinkedinProfileNormalizer;
use Dealroom\SocialsHelpers\Normalizers\LinkedinSchoolNormalizer;
use Dealroom\SocialsHelpers\Normalizers\LinkedinShowcaseNormalizer;
use Dealroom\SocialsHelpers\Normalizers\NormalizerInterface;
use Dealroom\SocialsHelpers\Normalizers\SoundcloudNormalizer;
use Dealroom\SocialsHelpers\Normalizers\SpotifyArtistNormalizer;
use Dealroom\SocialsHelpers\Normalizers\TikTokNormalizer;
use Dealroom\SocialsHelpers\Normalizers\TwitterNormalizer;
use Dealroom\SocialsHelpers\Normalizers\XNormalizer;
use Dealroom\SocialsHelpers\Normalizers\YoutubeNormalizer;
use PHPUnit\Framework\TestCase;

// phpcs:disable Generic.Files.LineLength.TooLong
class NormalizersTest extends TestCase
{
    public function testNormalizersFactory(): void
    {
        $twitterNormalizer = Factory::getForPlatform(TwitterNormalizer::PLATFORM);
        $facebookPageNormalizer = Factory::getForPlatform(FacebookPageNormalizer::PLATFORM);
        $facebookProfileNormalizer = Factory::getForPlatform(FacebookProfileNormalizer::PLATFORM);
        $linkedinCompanyProfileNormalizer = Factory::getForPlatform(LinkedinCompanyNormalizer::PLATFORM);

        $this->assertInstanceOf(TwitterNormalizer::class, $twitterNormalizer);
        $this->assertInstanceOf(FacebookPageNormalizer::class, $facebookPageNormalizer);
        $this->assertInstanceOf(FacebookProfileNormalizer::class, $facebookProfileNormalizer);
        $this->assertInstanceOf(LinkedinCompanyNormalizer::class, $linkedinCompanyProfileNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $twitterNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $facebookPageNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $facebookProfileNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $linkedinCompanyProfileNormalizer);
    }

    public function testTwitterNormalizer(): void
    {
        $twitterNormalizer = Factory::getForPlatform(TwitterNormalizer::PLATFORM);

        $values = [
            'http://www.twitter.com/codeschool/dasd?dsadasd' => 'https://twitter.com/codeschool',
            'https://www.twitter.com/codeschool?dasdad' => 'https://twitter.com/codeschool',
            'https://www.twitter.com/Codeschool?dasdad' => 'https://twitter.com/codeschool',
            'http://twitter.com/codeschool/dasd?dsadasd' => 'https://twitter.com/codeschool',
            'https://twitter.com/codeschool?dasdad' => 'https://twitter.com/codeschool',
            'https://twitter.com/#!/codeschool?dasdad' => 'https://twitter.com/codeschool',
            'https://twitter.com/@codeschool?dasdad' => 'https://twitter.com/codeschool',
            'https://twitter.com/code-school' => 'https://twitter.com/code',
            'https://twitter.com/code_school' => 'https://twitter.com/code_school',
            'https://twitter.com/greentextbooks#' => 'https://twitter.com/greentextbooks',
            'https://twitter.com/shopbop#cs=ov=73421773243,os=1,link=footerconnecttwitterlink\',' => 'https://twitter.com/shopbop',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $twitterNormalizer->normalize($source));
        }

        $this->expectException(NormalizeException::class);
        $twitterNormalizer->normalize('https://twitter.com/share');
    }

    public function testXNormalizer(): void
    {
        $twitterNormalizer = Factory::getForPlatform(XNormalizer::PLATFORM);

        $values = [
            'http://www.x.com/codeschool/dasd?dsadasd' => 'https://x.com/codeschool',
            'https://www.x.com/codeschool?dasdad' => 'https://x.com/codeschool',
            'https://www.x.com/Codeschool?dasdad' => 'https://x.com/codeschool',
            'http://x.com/codeschool/dasd?dsadasd' => 'https://x.com/codeschool',
            'https://x.com/codeschool?dasdad' => 'https://x.com/codeschool',
            'https://x.com/#!/codeschool?dasdad' => 'https://x.com/codeschool',
            'https://x.com/@codeschool?dasdad' => 'https://x.com/codeschool',
            'https://x.com/code-school' => 'https://x.com/code',
            'https://x.com/code_school' => 'https://x.com/code_school',
            'https://x.com/greentextbooks#' => 'https://x.com/greentextbooks',
            'https://x.com/shopbop#cs=ov=73421773243,os=1,link=footerconnecttwitterlink\',' => 'https://x.com/shopbop',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $twitterNormalizer->normalize($source));
        }

        $this->expectException(NormalizeException::class);
        $twitterNormalizer->normalize('https://x.com/share');
    }

    public function testFacebookPageNormalizer(): void
    {
        $facebookPageNormalizer = Factory::getForPlatform(FacebookPageNormalizer::PLATFORM);


        $values = [
            'https://www.facebook.com/dizzain/?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'http://www.facebook.com/dizzain?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'http://facebook.com/dizzain?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'https://facebook.com/dizzain?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'https://facebook.com/Dizzain?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'https://www.facebook.com/pages/fasdfadsfasdfsadf/126287147568059?pnref=lhc' => 'https://www.facebook.com/126287147568059',
            'https://www.facebook.com/PHPtoday-1025912177431644/?fref=ts' => 'https://www.facebook.com/phptoday-1025912177431644',
            'http://www.facebook.com/pages/The-bloomtrigger-project/125218650866978/fasdfas?asdas' => 'https://www.facebook.com/125218650866978',
            'http://www.facebook.com/pages/DealMarket/157833714232772' => 'https://www.facebook.com/157833714232772',
            'http://www.facebook.com/#!/pages/dealmarket/157833714232772' => 'https://www.facebook.com/157833714232772',
            'http://www.facebook.com/pages/san-diego-ca/layer3-security-services/207635209271099' => 'https://www.facebook.com/207635209271099',
            'http://www.facebook.com/pages/agility+inc./114838698562760' => 'https://www.facebook.com/114838698562760',
            'http://www.facebook.com/pages/karen-mali-m%c3%bc%c5%9favirlik-logo-muhasebe/194296120603783' => 'https://www.facebook.com/194296120603783',
            'http://www.facebook.com//pages/custom-case-guy/1445342082363874' => 'https://www.facebook.com/1445342082363874',
            'https://en-gb.facebook.com/wonderbill/' => 'https://www.facebook.com/wonderbill',
            'https://business.facebook.com/TectradeHQ/?business_id=284925295380988&ref=bookmarks' => 'https://www.facebook.com/tectradehq',
            'https://web.facebook.com/dermexpert/' => 'https://www.facebook.com/dermexpert',
            'https://m.facebook.com/umadic1/' => 'https://www.facebook.com/umadic1',
            'https://p-upload.facebook.com/epicvue/' => 'https://www.facebook.com/epicvue',
            'https://www.facebook.com/pages/Torrent-Pharmaceuticals-Limited/398754970290333' => 'https://www.facebook.com/398754970290333',
            'https://en-gb.facebook.com/pages/Torrent-Pharmaceuticals-Limited/398754970290333' => 'https://www.facebook.com/398754970290333',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $facebookPageNormalizer->normalize($source));
        }
    }

    public function testFacebookPageNormalizerErrors(): void
    {
        $facebookPageNormalizer = Factory::getForPlatform(FacebookPageNormalizer::PLATFORM);

        $values = [
            'http://www.facebook.com/home.php?tests#!/pages/dealmarket/157833714232772',
        ];

        $this->expectException(NormalizeException::class);
        foreach ($values as $source) {
            $facebookPageNormalizer->normalize($source);
        }
    }

    public function testFacebookProfileNormalizer(): void
    {
        $facebookProfileNormalizer = Factory::getForPlatform(FacebookProfileNormalizer::PLATFORM);

        $values = [
            'http://www.facebook.com/people/_/100000049946330' => 'https://www.facebook.com/profile.php?id=100000049946330',
            'http://www.facebook.com/profile.php?id=1294422029' => 'https://www.facebook.com/profile.php?id=1294422029',
            'http://www.facebook.com/profile.php?id=1294422029/' => 'https://www.facebook.com/profile.php?id=1294422029',
            'http://facebook.com/profile.php?id=1294422029' => 'https://www.facebook.com/profile.php?id=1294422029',
            'http://facebook.com/profile.php?id=1294422029&foo=bar' => 'https://www.facebook.com/profile.php?id=1294422029',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $facebookProfileNormalizer->normalize($source));
        }
    }

    //InstagramNormalizer
    public function testInstagramNormalizer(): void
    {
        $instagramNormalizer = Factory::getForPlatform(InstagramNormalizer::PLATFORM);

        $values = [
            'https://www.instagram.com/kevin' => 'https://www.instagram.com/kevin',
            'https://www.instagram.com/kevin/' => 'https://www.instagram.com/kevin',
            'https://instagram.com/kevin' => 'https://www.instagram.com/kevin',
            'http://www.instagram.com/kevin' => 'https://www.instagram.com/kevin',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $instagramNormalizer->normalize($source));
        }
    }

    public function testLinkedinCompanyNormalizer(): void
    {
        $linkedinCompanyProfileNormalizer = Factory::getForPlatform(LinkedinCompanyNormalizer::PLATFORM);

        $values = [
            'https://www.linkedin.com/company/dealroom/' => 'https://www.linkedin.com/company/dealroom/',
            'https://www.linkedin.com/company/dealroom' => 'https://www.linkedin.com/company/dealroom/',
            'http://www.linkedin.com/company/dealroom/' => 'https://www.linkedin.com/company/dealroom/',
            'https://linkedin.com/company/dealroom/' => 'https://www.linkedin.com/company/dealroom/',
            'https://www.linkedin.com/company/dealroom-co/' => 'https://www.linkedin.com/company/dealroom-co/',
            'https://www.linkedin.com/company/dealroom-co/contacts' => 'https://www.linkedin.com/company/dealroom-co/',
            'https://www.linkedin.com/company/vanesp-ib%C3%A9rica-transit%C3%A1rios-s-a-' => 'https://www.linkedin.com/company/vanesp-ibérica-transitários-s-a-/',
            'https://www.linkedin.com/company/novocomms%E8%AF%BA%E6%B2%83%E9%80%9A%E8%AE%AF%E7%A7%91%E6%8A%80/' => 'https://www.linkedin.com/company/novocomms诺沃通讯科技/',
            'https://www.linkedin.com/company/upjers-gmbh-&-co.-kg' => 'https://www.linkedin.com/company/upjers-gmbh-&-co.-kg/',
            "https://www.linkedin.com/company/trippin'-in" => "https://www.linkedin.com/company/trippin'-in/",
            'https://www.linkedin.com/company/magis_official' => 'https://www.linkedin.com/company/magis_official/',
            'https://www.linkedin.com/company/wonday%C2%AE/' => 'https://www.linkedin.com/company/wonday®/',
            'https://www.linkedin.com/company/cake-–-ridecake.com/' => 'https://www.linkedin.com/company/cake-–-ridecake.com/',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $linkedinCompanyProfileNormalizer->normalize($source));
        }
    }

    public function testLinkedinShowcaseNormalizer(): void
    {
        $linkedinShowcaseNormalizer = Factory::getForPlatform(LinkedinShowcaseNormalizer::PLATFORM);

        $values = [
            'https://www.linkedin.com/showcase/dealroom/' => 'https://www.linkedin.com/showcase/dealroom/',
            'https://www.linkedin.com/showcase/dealroom' => 'https://www.linkedin.com/showcase/dealroom/',
            'http://www.linkedin.com/showcase/dealroom/' => 'https://www.linkedin.com/showcase/dealroom/',
            'https://linkedin.com/showcase/dealroom/' => 'https://www.linkedin.com/showcase/dealroom/',
            'https://www.linkedin.com/showcase/dealroom-co/' => 'https://www.linkedin.com/showcase/dealroom-co/',
            'https://www.linkedin.com/showcase/dealroom-co/contacts' => 'https://www.linkedin.com/showcase/dealroom-co/',
            'https://www.linkedin.com/showcase/vanesp-ib%C3%A9rica-transit%C3%A1rios-s-a-' => 'https://www.linkedin.com/showcase/vanesp-ibérica-transitários-s-a-/',
            'https://www.linkedin.com/showcase/novocomms%E8%AF%BA%E6%B2%83%E9%80%9A%E8%AE%AF%E7%A7%91%E6%8A%80/' => 'https://www.linkedin.com/showcase/novocomms诺沃通讯科技/',
            'https://www.linkedin.com/showcase/upjers-gmbh-&-co.-kg' => 'https://www.linkedin.com/showcase/upjers-gmbh-&-co.-kg/',
            "https://www.linkedin.com/showcase/trippin'-in" => "https://www.linkedin.com/showcase/trippin'-in/",
            'https://www.linkedin.com/showcase/gsscloud_vital%E9%9B%B2%E7%AB%AF%E6%9C%8D%E5%8B%99%E5%AE%B6%E6%97%8F/about/' => 'https://www.linkedin.com/showcase/gsscloud_vital雲端服務家族/',
            'https://www.linkedin.com/showcase/wonday%C2%AE/' => 'https://www.linkedin.com/showcase/wonday®/',
            'https://www.linkedin.com/showcase/cake-–-ridecake.com/' => 'https://www.linkedin.com/showcase/cake-–-ridecake.com/',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $linkedinShowcaseNormalizer->normalize($source));
        }
    }

    public function testLinkedinSchoolNormalizer(): void
    {
        $linkedinSchoolNormalizer = Factory::getForPlatform(LinkedinSchoolNormalizer::PLATFORM);

        $values = [
            'https://www.linkedin.com/school/dealroom/' => 'https://www.linkedin.com/school/dealroom/',
            'https://www.linkedin.com/school/dealroom' => 'https://www.linkedin.com/school/dealroom/',
            'http://www.linkedin.com/school/dealroom/' => 'https://www.linkedin.com/school/dealroom/',
            'https://linkedin.com/school/dealroom/' => 'https://www.linkedin.com/school/dealroom/',
            'https://www.linkedin.com/school/dealroom-co/' => 'https://www.linkedin.com/school/dealroom-co/',
            'https://www.linkedin.com/school/dealroom-co/contacts' => 'https://www.linkedin.com/school/dealroom-co/',
            'https://www.linkedin.com/school/vanesp-ib%C3%A9rica-transit%C3%A1rios-s-a-' => 'https://www.linkedin.com/school/vanesp-ibérica-transitários-s-a-/',
            'https://www.linkedin.com/school/novocomms%E8%AF%BA%E6%B2%83%E9%80%9A%E8%AE%AF%E7%A7%91%E6%8A%80/' => 'https://www.linkedin.com/school/novocomms诺沃通讯科技/',
            'https://www.linkedin.com/school/upjers-gmbh-&-co.-kg' => 'https://www.linkedin.com/school/upjers-gmbh-&-co.-kg/',
            "https://www.linkedin.com/school/trippin'-in" => "https://www.linkedin.com/school/trippin'-in/",
            "https://www.linkedin.com/school/trippin'__in" => "https://www.linkedin.com/school/trippin'__in/",
            'https://www.linkedin.com/school/wonday%C2%AE/' => 'https://www.linkedin.com/school/wonday®/',
            'https://www.linkedin.com/school/cake-–-ridecake.com/' => 'https://www.linkedin.com/school/cake-–-ridecake.com/',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $linkedinSchoolNormalizer->normalize($source));
        }
    }

    public function testLinkedinProfileNormalizer(): void
    {
        $linkedinSchoolNormalizer = Factory::getForPlatform(LinkedinProfileNormalizer::PLATFORM);

        $values = [
            'https://www.linkedin.com/in/dealroom/' => 'https://www.linkedin.com/in/dealroom/',
            'https://www.linkedin.com/in/dealroom' => 'https://www.linkedin.com/in/dealroom/',
            'http://www.linkedin.com/in/dealroom/' => 'https://www.linkedin.com/in/dealroom/',
            'http://de.linkedin.com/in/dealroom/' => 'https://www.linkedin.com/in/dealroom/',
            'https://de.linkedin.com/in/peter-müller-81a8/' => 'https://www.linkedin.com/in/peter-müller-81a8/'
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $linkedinSchoolNormalizer->normalize($source));
        }
    }

    public function testTikTokNormalizer(): void
    {
        $tikTokNormalizer = Factory::getForPlatform(TikTokNormalizer::PLATFORM);

        $values = [
            'https://www.tiktok.com/@khaby.lame' => 'https://www.tiktok.com/@khaby.lame',
            'https://www.tiktok.com/@charlidamelio' => 'https://www.tiktok.com/@charlidamelio',
            'https://www.tiktok.com/@bts_official_bighit' => 'https://www.tiktok.com/@bts_official_bighit',
            'https://tiktok.com/@tiktok' => 'https://www.tiktok.com/@tiktok',
            'http://www.tiktok.com/@tiktok' => 'https://www.tiktok.com/@tiktok',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $tikTokNormalizer->normalize($source));
        }
    }

    public function testAppleMusicNormalizer(): void
    {
        $appleMusicNormalizer = Factory::getForPlatform(AppleMusicNormalizer::PLATFORM);

        $values = [
            'https://music.apple.com/us/artist/the-beatles/136975' => 'https://music.apple.com/artist/136975',
            'https://music.apple.com/us/artist/beatles/136975' => 'https://music.apple.com/artist/136975',
            'https://music.apple.com/artist/beatles/136975' => 'https://music.apple.com/artist/136975',
            'https://music.apple.com/artist/136975' => 'https://music.apple.com/artist/136975',
            'https://itunes.apple.com/us/artist/id136975' => 'https://music.apple.com/artist/136975',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $appleMusicNormalizer->normalize($source));
        }
    }

    public function testSoundcloudNormalizer(): void
    {
        $soundcloudNormalizer = Factory::getForPlatform(SoundcloudNormalizer::PLATFORM);

        $values = [
            'https://soundcloud.com/kx5-music' => 'https://soundcloud.com/kx5-music',
            'https://soundcloud.com/kx5official' => 'https://soundcloud.com/kx5official',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $soundcloudNormalizer->normalize($source));
        }
    }

    public function testSpotifyArtistNormalizer(): void
    {
        $spotifyArtistNormalizer = Factory::getForPlatform(SpotifyArtistNormalizer::PLATFORM);

        $values = [
            'https://open.spotify.com/artist/3WrFJ7ztbogyGnTHbHJFl2' => 'https://open.spotify.com/artist/3WrFJ7ztbogyGnTHbHJFl2',
            'spotify:artist:3WrFJ7ztbogyGnTHbHJFl2' => 'https://open.spotify.com/artist/3WrFJ7ztbogyGnTHbHJFl2',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $spotifyArtistNormalizer->normalize($source));
        }
    }

    public function testYoutubeNormalizer(): void
    {
        $youtubeNormalizer = Factory::getForPlatform(YoutubeNormalizer::PLATFORM);

        $values = [
            'https://www.youtube.com/channel/UCJow9j3zvZ4vK2ZjUwZc6Fw' => 'https://www.youtube.com/channel/UCJow9j3zvZ4vK2ZjUwZc6Fw',
            'https://www.youtube.com/user/Google' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/c/Google' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google/' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google/about' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google/videos' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google/playlists' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google/community' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google/channels' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google/featured' => 'https://www.youtube.com/Google',
            'https://www.youtube.com/Google/live' => 'https://www.youtube.com/Google',
            'https://youtube.com/Google' => 'https://www.youtube.com/Google',
            'http://www.youtube.com/Google' => 'https://www.youtube.com/Google',
        ];

        foreach ($values as $source => $result) {
            $this->assertEquals($result, $youtubeNormalizer->normalize($source));
        }
    }

    public function testNormalizerToId(): void
    {
        $values = [
            'https://twitter.com/codeschool' => [TwitterNormalizer::PLATFORM, 'codeschool'],
            'https://www.facebook.com/dizzain' => [FacebookPageNormalizer::PLATFORM, 'dizzain'],
            'https://www.facebook.com/profile.php?id=1294422029' => [FacebookProfileNormalizer::PLATFORM, '1294422029'],
            'https://www.linkedin.com/company/dealroom/' => [LinkedinCompanyNormalizer::PLATFORM, 'dealroom'],
            'https://www.linkedin.com/showcase/dealroom/' => [LinkedinShowcaseNormalizer::PLATFORM, 'dealroom'],
            'https://www.linkedin.com/school/dealroom//' => [LinkedinSchoolNormalizer::PLATFORM, 'dealroom'],
            'https://www.linkedin.com/in/dealroom//' => [LinkedinProfileNormalizer::PLATFORM, 'dealroom'],
        ];

        foreach ($values as $url => $row) {
            $this->assertEquals($row[1], Factory::getForPlatform($row[0])->normalizeToId($url));
        }
    }
}
