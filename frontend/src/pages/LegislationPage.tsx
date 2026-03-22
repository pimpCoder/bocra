import Card from '../components/Card'
import Section from '../components/Section'
import styles from './Page.module.css'

function LegislationPage() {
  return (
    <>
      <Section title="Legal Framework">
        <Card>
          <p className={styles.muted}>
            This section will host downloadable legislation and official notices
            in PDF format.
          </p>
          <ul>
            <li>Communications Regulatory Act (Placeholder)</li>
            <li>Licensing Regulations (Placeholder)</li>
            <li>Consumer Protection Guidelines (Placeholder)</li>
          </ul>
        </Card>
      </Section>
    </>
  )
}

export default LegislationPage
