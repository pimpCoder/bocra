import JobList from '../components/JobList'
import Section from '../components/Section'
import { jobs } from '../data/careers'
import styles from './Page.module.css'

function CareersPage() {
  return (
    <>
      <section className={styles.careersIntro}>
        <h1 className={styles.careersIntroTitle}>Careers at BOCRA</h1>

        <p className={styles.careersIntroText}>
          Botswana Communications Regulatory Authority (BOCRA) is the regulator
          of the Botswana communications sector, with responsibilities over
          telecommunications, broadcasting, postal and radio communication
          services. Technology is fast changing every aspect of our economy and
          telecommunications regulation is fast changing to keep pace with all
          these changes in the industry. This is an exciting time to be at the
          heart of this fast-moving sector. You can be part of it. Come and
          find out more about working for BOCRA and our current opportunities.
        </p>

        <h2 className={styles.careersSubheading}>Life At BOCRA</h2>
        <p className={styles.careersIntroText}>
          BOCRA is an organisation in which talented people work together,
          thrive and develop. We are committed to investing and supporting our
          Colleagues.
        </p>

        <h2 className={styles.careersSubheading}>What We Can Offer You</h2>
        <p className={styles.careersIntroText}>
          We believe that the reward package at BOCRA is based on much more
          than just salary. Our aim is to empower colleagues to undertake
          interesting and important work and we are committed to investing and
          supporting people to achieve their full potential.
        </p>

        <h2 className={styles.careersSubheading}>Professional Development</h2>
        <p className={styles.careersIntroText}>
          At BOCRA, people are our greatest asset, so developing our people is
          a fundamental part of our ethos. We take professional development very
          seriously, and encourage colleagues to seek continuous development to
          improve their performance in role. We also provide a variety of
          opportunities for colleagues to meet those needs, including:
        </p>
        <ul className={styles.careersBullets}>
          <li>a comprehensive set of internal training courses;</li>
          <li>investment to attend appropriate external courses;</li>
          <li>sponsorships for professional or academic qualifications; and</li>
          <li>memberships of professional bodies.</li>
        </ul>
        <p className={styles.careersIntroText}>
          We believe that our colleagues are best-placed to choose the benefits
          that are of most value to them, so we have designed a flexible
          benefits package to suit individual needs. The range of benefits that
          can be chosen reflects the flexible environment we aim to create.
        </p>

        <h2 className={styles.careersSubheading}>Benefits</h2>
        <p className={styles.careersIntroText}>Our standard benefits include:</p>
        <ul className={styles.careersBullets}>
          <li>Pension allowance</li>
          <li>25 days holiday</li>
          <li>Private Medical Insurance</li>
          <li>Life Assurance</li>
        </ul>
        <p className={styles.careersIntroText}>
          You can also choose from a wider range of flexible benefits,
          including the option to purchase additional annual leave, travel
          insurance, private medical cover for your family... and much more.
        </p>
      </section>

      <Section title="Join Our Team">
        <JobList jobs={jobs} />
      </Section>
    </>
  )
}

export default CareersPage
