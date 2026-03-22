import Section from '../components/Section'
import jobCardStyles from '../components/JobCard.module.css'
import jobListStyles from '../components/JobList.module.css'
import styles from './Page.module.css'

function TendersPage() {
  return (
    <>
      <Section title="Tenders">
        <div className={jobListStyles.container}>
          <article className={jobCardStyles.card}>
            <p className={jobCardStyles.category}>Tender Notice</p>
            <h3 className={jobCardStyles.title}>
              NOTICE OF ADJUDICATION DECISION - Supply and Installation of
              Solar Photovoltaic Panel System at BOCRA Head Office and
              Phakalane Spectrum House
            </h3>
            <p className={jobCardStyles.description}>
              <a
                href="https://www.bocra.org.bw/sites/default/files/tenders/Notice_of_Adjudication_Decision%5B84%5D.docx_.pdf"
                target="_blank"
                rel="noreferrer"
              >
                Open Tender Document (PDF)
              </a>
            </p>
            <div className={jobCardStyles.metaRow}>
              <span className={jobCardStyles.metaItem}>
                <strong>Status:</strong> Open
              </span>
              <span className={jobCardStyles.metaItem}>
                <strong>Closing Date:</strong> 27 March 2026
              </span>
            </div>
          </article>
        </div>

        <div className={styles.careersIntro}>
          <p className={styles.careersIntroText}>
            In order to ensure that the Botswana Communications Regulatory
            Authority is offered the best value-for-money, it has a thorough
            tendering process.
          </p>

          <h2 className={styles.careersSubheading}>Tendering Documents</h2>
          <p className={styles.careersIntroText}>
            Tender documents - usually called an Invitation to Tender (ITT) -
            will most likely contain the following sections:
          </p>
          <ul className={styles.careersBullets}>
            <li>Introduction - background information on the tender</li>
            <li>
              Tender Conditions - the legal parameters surrounding the tender
            </li>
            <li>
              Specification - the description of the supplies, service or works
              to be provided
            </li>
            <li>
              Instructions for Tender Submission - instructions for the bidders
            </li>
            <li>
              Qualitative Tender Response - qualitative questions to be answered
              by the bidder
            </li>
            <li>
              Pricing and Delivery Schedule - quantitative questions to be
              answered by the bidder
            </li>
            <li>Form of Tender - declaration to be signed by the bidder</li>
            <li>
              Certificate of Non-Collusion - declaration that the bidder has not
              colluded with any other bidder on the tender
            </li>
            <li>
              Draft of Proposed Contract - a draft of the contract which will be
              signed by the successful bidder
            </li>
            <li>Universal Access Service Fund</li>
            <li>
              Consultancy Services for the Development of Cost Models and
              Pricing Framework for ICT Services to Enhance Competition among
              Operations in Botswana
            </li>
            <li>
              Consultancy Services for a Market Study and the Development of a
              Licensing Framework for the Postal Sector in Botswana
            </li>
            <li>
              Review of Type Approval Technical Standards &amp; Procedures and
              Development of Broadcasting Technical Standards
            </li>
            <li>Notice of Tenders</li>
          </ul>
        </div>
      </Section>
    </>
  )
}

export default TendersPage
