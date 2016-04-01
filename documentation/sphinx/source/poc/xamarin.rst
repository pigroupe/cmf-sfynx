Xamarin POC
===========

1. Introduction
---------------

POC consists in create common mobile applicationfor IOS, ANDROID and WIN PHONE. Core development has to be released in c#.

    .. warning:: Under HERVE CLIQUET supervision

2. Conditions used
------------------

    * Windows 8.1 64 bits edition or newer
    * Visual Studio 2015 Pro edition or better
    * Xamarin edition pro
    * Apple Xcode license
    * Physical MAC (Apple) machine (for launching building on IOS)

3. Research base
----------------

    **Xamarin : what is it ?**

        Xamarin is THE actual module for making cross-platform mobile application.
        Xamarin is based on Windows c# mobile framework converted for working on IOS and ANDROID packages. The aim of this framework permits to build and to convert all an application made in c# to packages released in objective C or java.
        There are two ways to introduce Xamarin :

        * The first way consists in using Xamarin through its own IDE named Xamarin studio.

            .. note:: see `<https://xamarin.com/studio>`_

            Made for working on Apple machine and on Windows for Android developments, it permits only to create application for both IOS and ANDROID but no on WINPHONE.
            However, having a Xamarin license is mandatory in order to build final packages for this twice OS.

            But, keep in mind thaht it will be clearly mandatory to have a Xcode license developer in order to build for an Apple hardware.
            Despite the fact that we cannot build application for WINPHONE, it seems that Xamarin studio is really a good framework and has a great IDE with an easy access for beginner.

        * The second way consists in using centalised develoments on Windows desktop from Visual Studio 2015.

    **Development environment : on a Windows desktop**

        .. note:: see `<https://www.visualstudio.com/fr-fr/products/vs-2015-product-editions.aspx>`_

        Just like we said, if you choose to develop on the three actual better mobile OS on the market, the better way to do this is clearly using Visual Studio 2015 for Windows OS.
        Visual Studio 2015 is THE IDE from Microsoft, which permits to build native application for Windows regardless the media used.

        Despite of the language used (c#), Visual Studio 2015 comes with Xamarin package installed from scratch with it.
        Of course, it will be necessary to use a Xamarin license if you want to develop on others OS that WINPHONE.

        Visual Studio is clearly and easy friendly IDE, permits to build interfaces through an dedicated interface or permits to build them by Microsofot Blend for better conception.

4. Tests
--------

    The chosen solution is the windows one.
    Despite the fact that we have to get Visual Studio licenses, Visual Studio permits better developments that Xamarin Studio.

    **Windows conception**

        .. image:: ../_static/xamarin/vs.png

        Once a Visual Studio IDE is installed, you can create easilly cross-platform project in all in one solution.
        For Windows conception, an WINPHONE emulator is already integrated into Visual Studio, so it's not necessary to use a WINPHONE hardware device in order to develop this part of the application.

        .. image:: ../_static/xamarin/winphone_emulator.png

    **Android conception**

        As Winphone tests, Visual Studio and Xamarin provided an emulator for Android devices running directly on your Windows development machine.

        .. image:: ../_static/xamarin/android_emulator.jpg

        At this point you can note that, despite the fact that the code is in c#, all the code made for Android takes the same develoment schema as Android Studio, but in c# !

    **IOS conception**

        So, now the conflict point ! We are absolutely sure of one thing : Microsoft and Apple are the best rivals in the world, thaht's why it's impossible to build application for Windows on MAC and it's impossible to build Mac application from Windows.

        => *But, wait... you just said that it's possible to make application in cross-platform for this three OS, no ?* <=

        Yes ! Despite the war, Xamarin finds a way ! But for using it and build application on IOS you absolutely HAVE TO own a MAC.
        On Visual Studio, when you want to build and application for IOS, Visual Studio asks you to connect on a local MAC machine which call "remote console emulator".

        Xamarin provides specific agent on Windows which is in charge of retrieving MAC on your local server. For connecting it, your MAC machine has to be set properly.
        Xamarin had release a page which better explain it than us.

        .. note:: see `<https://developer.xamarin.com/guides/ios/getting_started/installation/windows/connecting-to-mac/>`_

        The fact is that, when you build an application through Visual Studio, a SSH connection is created from your Windows desktop to the MAC machine.
        The MAC machine runs at this point an IOS emulator; Xcode, installed on the MAC machine, builds packages received from your Windows phone and launch application on your IOS emulator.

        That's all !

4. Conclusion
-------------

    POC has been well released. The three os run correctly the same application after building it.
    HOWEVER, despite the fact that these three applications share the same common library, keep in mind that you absolutely have to construct interfaces for these three OS !
    Only the common libraries will be really common. But the interfaces have to be released in the three different formats, but in the same language (c#).

    Nevertheless, it's clearly a gaining time to do things this way. Common libraries contains models, translations, and even common comportments that can be used commonly by the three OS.
    And more, having a same development language for the three OS can be useful, mostly during fast developments sprints or hard debugging phases.

5. Status
---------

    Released and ready. Waiting for licenses and agreement.
