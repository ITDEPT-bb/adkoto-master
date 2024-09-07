<script setup>
import { Head, Link } from "@inertiajs/vue3";
import logo from "/public/img/adkoto.png";

defineProps({
    policy: String,
});
</script>

<template>
    <Head title="Privacy Policy" />

    <div
        class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full overflow-y-auto"
    >
        <div class="pt-4 bg-gray-100 dark:bg-gray-900">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <Link :href="route('adkoto')">
                    <img
                        :src="logo"
                        class="block h-10 sm:h-12 w-auto mb-6"
                        alt="Logo"
                    />
                </Link>

                <div
                    class="w-full sm:max-w-5xl mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg prose dark:prose-invert"
                >
                    <h1
                        class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                    >
                        Privacy and Confidentiality at Adkoto
                    </h1>

                    <!-- Privacy Content -->
                    <div
                        class="prose dark:prose-invert max-w-full px-2 text-gray-700 dark:text-gray-300"
                    >
                        <p class="mb-4">
                            Thank you for exploring Adkoto, the first
                            Filipino-developed social media platform that
                            empowers you to connect, shop, and express yourself!
                            We understand the importance of protecting your
                            privacy and are committed to adhering to the
                            principles outlined in the Data Privacy Act of 2012.
                        </p>

                        <h2
                            class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Building Trust Through Privacy
                        </h2>
                        <p class="mb-4">
                            At Adkoto, we prioritize the privacy and
                            confidentiality of our users. We're dedicated to
                            creating a secure and trustworthy environment for
                            everyone, especially those who share creative
                            content, engage in online shopping, and connect with
                            others. Here's how we approach user privacy and data
                            protection:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold"
                                    >Secure Data Handling:</span
                                >
                                We implement robust security measures to
                                safeguard your personal information from
                                unauthorized access, disclosure, alteration, or
                                destruction. Your data is treated with the
                                utmost care throughout your journey on Adkoto.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Confidentiality in User Interactions:</span
                                >
                                We recognize the sensitivity of user information
                                within Adkoto's various features. This includes
                                Tribekoto posts and discussions, Kalakalkoto
                                purchase history, and any personal details you
                                share on our platform. Rest assured, all your
                                information is treated confidentially to protect
                                your privacy.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Encryption for Secure Communication:</span
                                >
                                Communication within Adkoto, whether through
                                Tribekoto messages, Kalakalkoto transactions, or
                                general platform interactions, is fortified with
                                encryption protocols. This extra layer of
                                security ensures that your messages, project
                                submissions, and any information shared within
                                Adkoto remain secure and private.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Limited Access to Personal
                                    Information:</span
                                >
                                Access to user data is strictly limited to
                                authorized personnel at Adkoto. Our team members
                                who require this information for legitimate
                                purposes, such as technical support or security
                                audits, undergo thorough vetting procedures. We
                                ensure that only those essential for providing
                                our services have access to your data.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Third-Party Data Handling:</span
                                >
                                When integrating third-party services into
                                Adkoto, we maintain rigorous standards. We
                                carefully vet all partners to ensure they meet
                                our strict privacy and confidentiality
                                requirements. Your data is only shared
                                externally with your explicit consent and under
                                secure conditions.
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Transparency through our Privacy Policy:
                        </h4>
                        <p class="mb-4">
                            At Adkoto, we prioritize transparency. Our Privacy
                            Policy serves as a clear and comprehensive guide
                            explaining how we collect, use, and protect your
                            data. We encourage all users to review this document
                            to understand how we safeguard your privacy on
                            Adkoto.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            What Information Do We Collect?
                        </h4>
                        <p class="mb-4">
                            To provide the best possible experience on Adkoto,
                            we collect various types of information. Here's a
                            breakdown of what we gather:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold"
                                    >Personal Information:</span
                                >
                                <br />
                                To create your Adkoto account and ensure secure
                                access to our features, we collect basic
                                personal information like your name, email
                                address, and contact details. This allows us to
                                verify your identity and facilitate
                                communication within the Adkoto community.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Understanding Your Preferences:</span
                                >
                                <br />
                                When you interact with Adkoto, we collect data
                                about your interests and preferences. This might
                                include the types of content you engage with on
                                Tribekoto, the products you browse on
                                Kalakalkoto, or the types of advertisements you
                                find most relevant. By understanding your
                                preferences, we can tailor your Adkoto
                                experience and recommend content, products, or
                                features you might enjoy.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Device and Usage Information:</span
                                >
                                <br />
                                We collect information about the devices you use
                                to access Adkoto (such as your phone or
                                computer) and how you interact with our
                                platform. This includes details like IP
                                addresses, browser types, and the pages you
                                visit. This data helps us optimize your
                                experience for different devices and ensure
                                smooth functionality across platforms.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Website Analytics and Optimization:</span
                                >
                                <br />
                                To personalize your Adkoto journey and deliver
                                content relevant to your interests, we utilize
                                cookies and similar technologies. These also
                                help us analyze website performance and identify
                                areas for improvement. For detailed information
                                on how we use cookies, please refer to our
                                separate Cookie Policy.
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Your Contributions Shape Our Community:
                        </h4>
                        <p class="mb-4">
                            At Adkoto, we value your feedback and contributions!
                            The information you share, such as comments on
                            posts, reviews on Kalakalkoto products, or
                            suggestions for improvements, helps enrich our
                            platform and benefits everyone. All your
                            contributions are stored securely in accordance with
                            our Privacy Policy.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Keeping the Conversation Going:
                        </h4>
                        <p class="mb-4">
                            We collect data from your communication with us,
                            including emails, messages, and feedback within
                            Adkoto. This allows us to effectively address your
                            inquiries, provide support, and continuously improve
                            your Adkoto experience.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Third-Party Integrations: Expanding Your Toolkit
                        </h4>
                        <p class="mb-10">
                            When you connect third-party services (like design
                            tools or portfolio platforms) with your Adkoto
                            account, we may collect some data from these
                            integrations, but only according to your privacy
                            settings with those external services. This
                            integration allows for a smoother user experience
                            and lets you leverage your favorite tools alongside
                            all that Adkoto offers.
                        </p>

                        <h1
                            class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                        >
                            OUR DISCLOSURE OF YOUR INFORMATION
                        </h1>
                        <p class="mb-4">
                            We are committed to transparency regarding your
                            information within the Adkoto platform. We will
                            disclose information only in specific situations
                            that enhance your experience and improve our
                            services, always complying with relevant laws and
                            regulations.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Legal Requirements:
                        </h4>
                        <p class="mb-4">
                            In certain instances, we may disclose personal
                            information to comply with legal requirements,
                            enforce our Terms and Conditions, address
                            intellectual property claims, or protect the safety
                            and rights of any individual.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Enhancing Your Adkoto Experience:
                        </h4>
                        <p class="mb-4">
                            To provide exceptional customer support and
                            guidance, we may access and use your personal
                            information. This allows us to address your
                            inquiries, offer tailored recommendations on
                            Kalakalkoto, or suggest Tribekoto content relevant
                            to your interests, ultimately improving your overall
                            experience on Adkoto.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Third-Party Collaboration:
                        </h4>
                        <p class="mb-4">
                            We may collaborate with third-party providers of
                            tools or services that integrate with Adkoto to
                            elevate your experience. These providers may have
                            access to your information only for specific
                            purposes like software training, technical support,
                            or industry-related projects, all according to your
                            privacy settings with those external services.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Your Consent Matters:
                        </h4>
                        <p class="mb-4">
                            We will always obtain your explicit consent before
                            sharing your information with a specific third
                            party. For example, this could involve opting in to
                            receive emails from a partner organization. We will
                            always be clear about the purpose of data sharing
                            before proceeding.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            What We Don't Do:
                        </h4>
                        <p class="mb-4">
                            Adkoto is committed to responsible data practices.
                            We will never sell or rent your personal information
                            to third-party marketers. Your information is used
                            only for the purposes outlined in this policy.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Your Control Over Your Data:
                        </h4>
                        <p class="mb-4">
                            We respect your right to manage your information.
                            Please refer to the "Your Rights" section of our
                            Privacy Policy for details on how to access, update,
                            or request deletion of your information.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Keeping You Informed:
                        </h4>
                        <p class="mb-10">
                            Transparency is an ongoing commitment at Adkoto. We
                            will update this policy as needed and encourage you
                            to review it periodically to stay informed about how
                            we handle your data.
                        </p>

                        <h1
                            class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                        >
                            USING YOUR INFORMATION TO ENHANCE YOUR EXPERIENCE
                        </h1>
                        <p class="mb-4">
                            At Adkoto, we use your information to provide the
                            best possible experience across our platform. Here's
                            how:
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Optimizing Your Adkoto Journey:
                        </h4>
                        <p class="mb-4">
                            We use your information to deliver the features and
                            services you request. This includes processing your
                            account signup, personalizing your Tribekoto feed,
                            and facilitating secure transactions on Kalakalkoto.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Keeping You Informed:
                        </h4>
                        <p class="mb-4">
                            We may use your contact information to send
                            important updates, announcements, and promotional
                            offers related to Adkoto and its features. This
                            could include information about new Tribekoto
                            features, special Kalakalkoto deals, or targeted
                            Adkoto promotions relevant to your interests (with
                            your consent, of course!).
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Continuously Improving Adkoto:
                        </h4>
                        <p class="mb-4">
                            We analyze website usage data to understand how
                            people interact with Adkoto. This helps us identify
                            areas for improvement, personalize your experience,
                            and ensure the platform functions smoothly for
                            everyone.
                        </p>

                        <h1
                            class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                        >
                            NAVIGATING THIRD-PARTY CONTENT
                        </h1>
                        <p class="mb-4">
                            At Adkoto, we're committed to creating a safe and
                            inspiring space for you to connect, shop, and
                            express yourself. This includes being transparent
                            about third-party content you might encounter on our
                            platform.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Understanding Third-Party Content:
                        </h4>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold">Advertisements:</span>
                                You might see ads from companies offering design
                                tools, gadgets, or services that could enhance
                                your creative journey. While we carefully choose
                                our advertisers, Adkoto has no control over
                                their privacy practices. Always review their
                                privacy policies before interacting with their
                                ads.
                            </li>
                            <li>
                                <span class="font-bold">External Links:</span>
                                Adkoto may include links to external websites,
                                such as designer portfolios, industry blogs,
                                online communities, or relevant learning
                                resources. These links are provided for your
                                convenience and information only. We are not
                                responsible for the content or privacy practices
                                of these external sites.
                            </li>
                        </ul>
                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Tips for Safe Third-Party Interaction:
                        </h4>
                        <ul class="list-disc pl-5 space-y-2 mb-10">
                            <li>
                                <span class="font-bold">Be Cautious:</span>
                                Before clicking on a link or ad, take a moment
                                to assess its legitimacy. Look for familiar
                                logos or brand names, and avoid anything that
                                seems too good to be true.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Review Privacy Policies:</span
                                >
                                If you choose to visit a third-party website,
                                take some time to review their privacy policy
                                before submitting any personal information.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Protect Your Data:</span
                                >
                                Never share your personal information with a
                                third-party website unless you are confident in
                                their security practices.
                            </li>
                        </ul>

                        <h1
                            class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                        >
                            YOUR ACCOUNT, YOUR SECURITY
                        </h1>
                        <p class="mb-4">
                            At Adkoto, we understand the importance of
                            safeguarding your account information. Here's how we
                            prioritize security:
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Robust Security Measures:
                        </h4>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold"
                                    >Strong Password Policies:</span
                                >
                                We enforce strong password creation to protect
                                your account from unauthorized access. This
                                typically involves minimum password lengths and
                                a combination of uppercase and lowercase
                                letters, numbers, and symbols.
                            </li>
                            <li>
                                <span class="font-bold">Secure Logins:</span>
                                Adkoto utilizes a secure connection (HTTPS)
                                during logins. This encrypts data transmitted
                                between your device and our servers, adding an
                                extra layer of protection.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Regular Security Updates:</span
                                >
                                We implement a rigorous schedule of security
                                updates to address any potential vulnerabilities
                                on our platform and systems.
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            User Education for Enhanced Security:
                        </h4>
                        <p class="mb-4">
                            We believe in empowering users. We may provide
                            resources or tips for creating strong passwords and
                            practicing safe online habits.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Here's how you can be your own security partner:
                        </h4>
                        <ul class="list-disc pl-5 space-y-2 mb-10">
                            <li>
                                <span class="font-bold"
                                    >Choose a Strong and Unique Password:</span
                                >
                                Avoid easily guessable information like
                                birthdays or pet names.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Practice Safe Online Habits:</span
                                >
                                Be cautious of suspicious links or unknown
                                files. Be wary of phishing attempts designed to
                                steal your login information.
                            </li>
                        </ul>

                        <h1
                            class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                        >
                            SECURITY
                        </h1>
                        <p class="mb-4">
                            At Adkoto, we understand the importance of keeping
                            your information safe and secure. Here's how we
                            prioritize your data security:
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Guarding Your Information:
                        </h4>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold">Secure Servers:</span>
                                Your data is stored on secure servers protected
                                by firewalls and intrusion detection systems.
                                These measures act as a shield, preventing
                                unauthorized access and data breaches.
                            </li>
                            <li>
                                <span class="font-bold">Encrypted Data:</span>
                                We utilize industry-standard data encryption
                                practices to protect sensitive information, both
                                while it's being transferred (in transit) and
                                when it's stored (at rest). This scrambles your
                                data, making it unreadable to anyone who
                                shouldn't see it.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Regular Security Audits:</span
                                >
                                We conduct regular security audits to identify
                                and address any potential weaknesses in our
                                systems, proactively safeguarding your
                                information.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Staying Up-to-Date:</span
                                >
                                We maintain a rigorous update schedule for our
                                platform's software and applications. This
                                ensures we have the latest security patches and
                                protections in place.
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Education is Key:
                        </h4>
                        <p class="mb-4">
                            We believe an informed user is a secure user. We may
                            provide resources and tips for creating strong
                            passwords, recognizing phishing attempts, and
                            practicing safe online habits.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Account Protection Measures (as detailed elsewhere):
                        </h4>
                        <p class="mb-4">
                            We enforce strong password policies, secure login
                            protocols, and offer optional two-factor
                            authentication to further fortify your account
                            security.
                        </p>
                        <p class="mb-10">
                            At Adkoto, we're committed to providing a platform
                            where you can focus on what matters most - honing
                            your creative skills. By implementing robust
                            security measures and collaborating with our users,
                            we can create a safe and inspiring space for future
                            innovators. If you have any questions about our
                            security practices, please don't hesitate to contact
                            us.
                        </p>

                        <h1
                            class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                        >
                            ADKOTO COOKIE POLICY
                        </h1>
                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            A Clear and User-Friendly Experience
                        </h4>
                        <p class="mb-4">
                            At Adkoto, we strive to create an inspiring and
                            user-friendly experience for everyone interested in
                            the world of design. This Cookie Policy explains how
                            we use cookies and similar technologies to
                            personalize your experience and ensure our platform
                            functions smoothly.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Understanding Cookies:
                        </h4>
                        <p class="mb-4">
                            Cookies are small data files that a website stores
                            on your device when you visit. Think of them like
                            tiny memory chips that remember your preferences and
                            activity on the site. This can include things like:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                Login information (if you have an Adkoto
                                account)
                            </li>
                            <li>Recently viewed products on Kalakalkoto</li>
                            <li>Preferred language on Tribekoto</li>
                            <li>
                                Items added to your Kalakalkoto shopping cart
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Why We Use Cookies:
                        </h4>
                        <p class="mb-4">
                            Adkoto utilizes cookies for several key purposes:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                <span class="font-bold"
                                    >Enhanced Functionality:</span
                                >
                                Cookies help us remember your preferences, so
                                you don't have to re-enter information every
                                time you visit Adkoto. This streamlines your
                                experience and lets you focus on exploring the
                                creative content we offer.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Website Analytics:</span
                                >
                                Cookies provide valuable insights into how
                                visitors interact with Adkoto. This data helps
                                us understand user preferences, identify areas
                                for improvement, and ultimately deliver a more
                                engaging experience for everyone.
                            </li>
                            <li>
                                <span class="font-bold"
                                    >Personalized Content:</span
                                >
                                By understanding your browsing habits, we can
                                tailor the content you see on Adkoto to your
                                interests. This might involve showcasing design
                                portfolios relevant to your preferred style or
                                highlighting upcoming events on Tribekoto that
                                align with your creative goals.
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            You Are In Control:
                        </h4>
                        <p class="mb-4">
                            Most web browsers allow you to manage your cookie
                            preferences. You can choose to:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 mb-6">
                            <li>
                                Accept all cookies (default setting for most
                                browsers)
                            </li>
                            <li>
                                Block all cookies (may limit functionality on
                                some websites)
                            </li>
                            <li>
                                Be notified before a cookie is placed (allows
                                you to accept or reject cookies on a
                                case-by-case basis)
                            </li>
                        </ul>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Protecting Your Privacy:
                        </h4>
                        <p class="mb-4">
                            Adkoto is committed to protecting your privacy. The
                            information we collect through cookies is used
                            solely to improve your experience on our platform
                            and does not contain any personally identifiable
                            data (unless you have created an Adkoto account).
                        </p>
                        <p class="mb-10">
                            If you have any questions about our Cookie Policy,
                            please don't hesitate to contact us. We are happy to
                            provide further clarification.
                        </p>

                        <h1
                            class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                        >
                            YOUR RIGHTS ON ADKOTO
                        </h1>
                        <p class="mb-4">
                            At Adkoto, we believe in transparency and empowering
                            you to control your information. This section
                            outlines your choices regarding your data on our
                            platform:
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Access Your Information:
                        </h4>
                        <p class="mb-4">
                            You have the right to request a copy of the
                            information we hold about your activity on Adkoto.
                            This may include your browsing history, preferences
                            you've set (like language or Kalakalkoto filters),
                            and any limited data collected through cookies (as
                            explained in our Cookie Policy).
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Keeping Your Information Up-to-Date:
                        </h4>
                        <p class="mb-4">
                            If any of your information changes, you have the
                            right to request an update. This ensures we have
                            accurate records and can continue to personalize
                            your Adkoto experience effectively.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Data Deletion:
                        </h4>
                        <p class="mb-4">
                            In certain circumstances, you may have the right to
                            request the deletion of your personal information
                            from our systems. This right may be subject to
                            limitations, such as legal requirements or the
                            technical feasibility of erasing specific data
                            points.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Exercising Your Rights:
                        </h4>
                        <p class="mb-4">
                            We make it easy for you to exercise your data
                            rights. If you wish to access, update, or request
                            deletion of your information, please contact us
                            through the methods provided on our website. We will
                            respond to your request promptly and in accordance
                            with applicable regulations.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Transparency Commitment:
                        </h4>
                        <p class="mb-10">
                            Adkoto is committed to clear and transparent
                            communication regarding your data privacy. We will
                            keep this policy updated to reflect any changes in
                            our practices and ensure you remain informed about
                            your choices.
                        </p>

                        <h1
                            class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200"
                        >
                            KEEPING YOU INFORMED: UPDATES TO OUR PRIVACY POLICY
                        </h1>
                        <p class="mb-4">
                            At Adkoto, we're dedicated to keeping pace with the
                            ever-evolving digital world. This includes regularly
                            reviewing and updating our Privacy Policy to ensure
                            it reflects the latest practices and safeguards your
                            privacy.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Transparency in Updates:
                        </h4>
                        <p class="mb-4">
                            Whenever significant changes are made to our Privacy
                            Policy, we'll provide clear and concise
                            notifications. These will be prominently displayed
                            on Adkoto or communicated directly to you.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            Your Right to Know:
                        </h4>
                        <p class="mb-4">
                            The updated policy will always be readily available
                            on our website. This allows you to easily review the
                            changes and understand how your information is
                            handled.
                        </p>

                        <h4
                            class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4"
                        >
                            A Commitment to Improvement:
                        </h4>
                        <p class="mb-4">
                            Our goal with any policy updates is to enhance user
                            privacy and strengthen the security measures
                            protecting your information on Adkoto.
                        </p>
                        <p class="mb-4">
                            We encourage you to periodically review our Privacy
                            Policy to stay informed about any updates. If you
                            have any questions or concerns, please don't
                            hesitate to contact us. We value open communication
                            and your feedback.
                        </p>
                        <p class="mb-4">
                            By continuing to use Adkoto, you acknowledge your
                            understanding of this commitment to ongoing policy
                            updates.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
