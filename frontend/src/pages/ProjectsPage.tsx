import Section from '../components/Section'
import styles from './Page.module.css'

function ProjectsPage() {
  return (
    <>
      <Section title="Projects">
        <div className={styles.careersIntro}>
          <h2 className={styles.careersSubheading}>Country Code Top Level Domain</h2>
          <p className={styles.careersIntroText}>
            The Government of Botswana through the then Ministry of
            Communications, Science and Technology (MCST) has mandated BOCRA to
            perform the regulatory and administrative functions for the
            registration of the .bw domain name. The Ministry further mandated
            that:
          </p>
          <ul className={styles.careersBullets}>
            <li>
              the ISPs should be allowed to perform the retail functions by
              registering and selling the .bw domain name;
            </li>
            <li>
              a Technical Advisory Committee should be appointed by BOCRA with
              representatives from all key stakeholders, i.e. ISPs, PTOs etc, to
              guide the operations of the .bw domain registry; and
            </li>
            <li>
              in the long term the responsibility for the dot.bw domain
              management would be delegated to the proposed industry regulator
              through legislation.
            </li>
          </ul>
          <p className={styles.careersIntroText}>
            Following the mandate from the then MCST, the Technical Advisory
            Committee (TAC) composed of key industry stakeholders was formed and
            inaugurated on 28 April 2010. Nine stakeholders sit as members of TAC
            and these are: BOCRA, BOCCIM, BITS, Mascom Wireless, Orange Botswana,
            BTC, DIT, BISPA and UB. The role of TAC includes among others:
          </p>
          <ul className={styles.careersBullets}>
            <li>
              to identify the needs, concerns and interest of internet community;
            </li>
            <li>
              to promote the objectives of a fair level playing field and provide
              regular reports and updates to stakeholders;
            </li>
            <li>
              to give specialist advice where appropriate and to consider and
              recommend strategies for the continuation and development of the
              ccTLD; and
            </li>
            <li>
              to represent the country in regional and international meetings
              concerning ccTLD as and when required.
            </li>
          </ul>
          <p className={styles.careersIntroText}>
            As part of the TAC composition requirements from BOCRA, a chairperson
            and vice-chairperson were chosen among the members to facilitate TAC
            meetings. In line with promoting team effectiveness, TAC decided to
            establish two sub-committees to work on policy formulation and public
            awareness strategy. The two sub-committees, namely Policy
            Sub-Committee (BTC, Law Society, BTA, BISPA and DIT as members) and
            Public Awareness Sub-Committee (BITS, DIT, UB and BOCRA as members),
            meet at different times to TAC meetings to work on their objectives.
          </p>
          <p className={styles.careersIntroText}>Find the TAC Action plan here.</p>

          <h2 className={styles.careersSubheading}>Infrastructure Sharing</h2>
          <p className={styles.careersIntroText}>
            The growth of telecommunications services, especially mobile
            telephony, since 1996 has led to the worldwide proliferation of
            telecommunications infrastructure across towns, cities and the
            countryside. This proliferation has led to negative visual impacts on
            cityscape and landscape as they have no respect to important
            aesthetic/scenic views and amenity of both built and natural
            environments.
          </p>
          <p className={styles.careersIntroText}>
            In view thereof, BOCRA in collaboration with the Department of
            Environmental Affairs and public telecommunications operators has
            established guidelines to facilitate the sharing of all passive
            elements of communications infrastructure among operators.
          </p>

          <h3 className={styles.careersSubheading}>Objectives Of The Guidelines</h3>
          <p className={styles.careersIntroText}>
            The primary object of these Guidelines is to establish a framework
            within which communications operators can negotiate and conclude
            sharing arrangements for passive infrastructure, and for that purpose,
            specifically to:
          </p>
          <ul className={styles.careersBullets}>
            <li>
              ensure that the incidence of unnecessary duplication of passive
              infrastructure is minimised or completely avoided;
            </li>
            <li>
              protect the environment by reducing the proliferation of
              infrastructure and facilities installations or deployment;
            </li>
            <li>
              promote fair competition through equal access being granted to the
              passive infrastructure of operators, where applicable on mutually
              agreed terms;
            </li>
            <li>
              minimise operators&apos; expenditure on supporting infrastructures and
              free more funds for investment in core network equipment, innovative
              and affordable services; and
            </li>
            <li>
              encourage operators to take public health and safety and the
              environment into account when constructing and/or deploying
              infrastructure.
            </li>
          </ul>

          <h2 className={styles.careersSubheading}>Digital Switchover Process</h2>
          <p className={styles.careersIntroText}>
            The International Telecommunication Union (ITU) convened a Regional
            Radiocommunications Conference (RRC 06) to plan for digital
            broadcasting on frequency bands VHF (band III 174-230 MHz), and UHF
            (band IV and V 470-862 MHz) for ITU Region 1 in 2006. The RRC 06
            agreed that by 2015 all countries in the African and European Region
            should have completed digital switchover.
          </p>
          <p className={styles.careersIntroText}>
            In Botswana, a Reference Group was established on 07 February 2008 to
            kick start the digital switchover/migration process.
          </p>
          <p className={styles.careersIntroText}>
            For more information visit{' '}
            <a
              href="http://www.godigital.org.bw/"
              target="_blank"
              rel="noreferrer"
            >
              http://www.godigital.org.bw/
            </a>
            .
          </p>
        </div>
      </Section>
    </>
  )
}

export default ProjectsPage
